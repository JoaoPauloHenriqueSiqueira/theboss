<?php

namespace App\Services;

use App\Library\Format;
use App\Repositories\Contracts\SaleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Events\NewMessage;
use App\Transformers\SaleTransformer;

class SaleService
{
    protected $repository;
    protected $productService;
    protected $clientService;
    protected $userService;
    protected $statusService;
    protected $carbon;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        SaleRepositoryInterface $repository,
        ProductService $productService,
        ClientService $clientService,
        StatusService $statusService,
        UserService $userService,
        Carbon $carbon
    ) {
        $this->repository = $repository;
        $this->productService = $productService;
        $this->clientService = $clientService;
        $this->statusService = $statusService;
        $this->userService = $userService;
        $this->carbon = $carbon;
    }

    /**
     * All repository
     */
    public function all()
    {
        return $this->repository->orderBy('name')->get();
    }

    /**
     * Get repository
     */
    public function get($dateFilter = null)
    {
        if (!$dateFilter) {
            $dateFilter = $this->carbon::now();
        }

        $dateFilter = $this->carbon->parse($dateFilter);
        $start = $dateFilter->copy()->startOfDay();
        $finish = $dateFilter->copy()->endOfDay();

        $filterColumns = ['company_id' => Auth::user()->company_id];
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns, $start, $finish) {
            return $query->whereBetween('date_sale', [$start, $finish])->where($filterColumns)->orderBy('date_sale', 'DESC');
        });

        return $list;
    }


    public function searchDate($dateFilter, $isMonth)
    {
        $dateFilter = $this->carbon->parse($dateFilter);

        $start = $dateFilter->copy()->startOfDay()->startOfDay();
        $finish = $dateFilter->copy()->endOfDay()->endOfDay();

        if ($isMonth) {
            $start = $dateFilter->copy()->startOfDay()->startOfMonth();
            $finish = $dateFilter->copy()->endOfDay()->endOfMonth();
        }

        $filterColumns = ['company_id' => Auth::user()->company_id];
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns, $start, $finish) {
            return $query->whereBetween('date_sale', [$start, $finish])->where($filterColumns);
        });

        return $list->get();
    }

    public function searchBetweenDates($request, $dateStartFilter, $dateEndFilter)
    {
        $dateStartFilter = $this->carbon->parse($dateStartFilter);
        $dateEndFilter = $this->carbon->parse($dateEndFilter);

        $start = $dateStartFilter->copy()->startOfDay()->startOfDay();
        $finish = $dateEndFilter->copy()->endOfDay()->endOfDay();

        $filterColumns = $this->makeParamsFilter($request);

        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns, $start, $finish) {
            return $query->whereBetween('date_sale', [$start, $finish])->where($filterColumns)->orderBy('date_sale', 'DESC');
        })->paginate(10);

        foreach($list as $sale){
            foreach($sale->products as $product){
                $product['product_sale_value'] = Format::moneyWithoutSymbol($product['pivot']['sale_value']);
            }
        }

        return $list;
    }

    public function search($request)
    {
        $dateFilter = Arr::get($request, 'search_sale_date', false);

        if (!$dateFilter) {
            $filterColumns = $this->makeParamsFilter($request);
            $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
                return $query->where($filterColumns)->orderBy('created_at', 'DESC');
            });

            return $list;
        }

        $dateFilter = $this->carbon->parse($dateFilter);
        $start = $dateFilter->copy()->startOfDay();
        $finish = $dateFilter->copy()->endOfDay();

        $filterColumns = $this->makeParamsFilter($request);
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns, $start, $finish) {
            return $query->whereBetween('date_sale', [$start, $finish])->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list;
    }

    private function makeParamsFilter($request)
    {
        $companyId = $request->header('Company');
        
        if (!$companyId) {
            $companyId = Auth::user()->company_id;
        }

        $filterColumns = ['company_id' => $companyId];

        if (Arr::get($request, 'search_client_id')) {
            array_push($filterColumns, ['client_id', '=',  Arr::get($request, 'search_client_id')]);
        }


        return  $filterColumns;
    }

   
    public function find($taskId)
    {
        return $this->repository->find($taskId)->toArray();
    }

    public function save($request)
    {
        if ($request->validated()) {
            $saleId = Arr::get($request, "id");
            $companyId = Auth::user()->company_id;

            if (!$this->validClient($request)) {
                return redirect()->back()->with('message', 'Cliente não pertence à sua base');
            }

            if ($saleId) {
                $sale = $this->repository->find($saleId);
                $sale->products()->detach();
                $sale->status()->detach();
            }

            $request = $this->makeSale($request);
            $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());

            $saleProducts = $this->addProducts($request, $response, $companyId);

            if (!Arr::get($saleProducts, 'status')) {
                $response->delete();
                return redirect()->back()->with('message', Arr::get($saleProducts, 'message'));
            }

            $amountTotal = Arr::get($saleProducts, 'total');
            $response =  $this->repository->updateOrCreate(["id" => Arr::get($response, "id")], ['amount_total' => $amountTotal]);
            $statuses = Arr::get($request, "statuses", []);
            $responseStatus = $this->addStatus($statuses, $response, $companyId);

            if (!$responseStatus) {
                $response->delete();
                return redirect()->back()->with('message', 'Um dos status enviados, não está na sua base.');
            }

            if ($response) {
                return redirect()->back()->with('message', 'Registro criado/atualizado!');
            }

            return redirect()->back()->with('message', 'Ocorreu algum erro');
        }
    }

    public function listClientApi($request)
    {
        $clientId = $this->clientService->findClientPasswordMail($request);
       
        if(!$clientId){
            return false;
        }

        $request['search_client_id'] = $clientId->id;
        $perPage = $request->query('per_page');

        $filterColumns = $this->makeParamsFilter($request);
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        })->paginate($perPage);



        $items = (new SaleTransformer)->transform($list->items());
        $list->setCollection($items);
        return $list;
    }

    public function saveAPI($request)
    {
        if ($request->validated()) {

            $saleId = Arr::get($request, "id");

            $companyId = $request->header('Company');
            if (!$this->validClient($request)) {
                return response()->json(['message' => "Cliente não pertence à sua base"], 422);
            }

            if (!$this->validPasswordClient($request)) {
                return response()->json(['message' => "Senha do cliente não confere"], 422);
            }

            if ($saleId) {
                $sale = $this->repository->find($saleId);
                $sale->products()->detach();
                $sale->status()->detach();
            }

            if (!$this->userService->checkCompany($request)) {
                return response()->json(['message' => "Esse usuário não pertence a essa empresa"], 422);
            }

            $request = $this->makeSale($request);

            $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());

            $saleProducts = $this->addProducts($request, $response, $companyId);

            if (!Arr::get($saleProducts, 'status')) {
                $response->delete();
                return response()->json(['message' =>  Arr::get($saleProducts, 'message')], 405);
            }

            $amountTotal = Arr::get($saleProducts, 'total');
            $response =  $this->repository->updateOrCreate(["id" => Arr::get($response, "id")], ['amount_total' => $amountTotal]);
            
            $sale = $this->repository->find(Arr::get($response, "id"));
            broadcast(new NewMessage($sale, $companyId))->toOthers();

            if ($response) {
                return response()->json(['message' => "Registro criado/atualizado!"], 201);
            }

            return response()->json(['message' => "Ocorreu um erro"], 500);
        }

        return response()->json(['message' => "Ocorreu um erro"], 500);
    }

    private function makeSale($request)
    {
        $companyId = $request->header('Company');
        if (!$companyId) {
            $companyId = Auth::user()->company_id;
        }

        $userId = Arr::get($request, "user_id");
        if (!$userId) {
            $userId = Auth::user()->id;
        }
        
        unset($request['amount_total']);
        $request['date_sale'] = $this->carbon->parse(Arr::get($request, "sale_date") . Arr::get($request, "sale_time"));
        $request['company_id'] = $companyId;
        $request['user_id'] = $userId;
        return $request;
    }

    private function validClient($request)
    {
        $clientId = Arr::get($request, "client_id");
        $companyId = $request->header('Company');

        if (!$companyId) {
            $companyId = Auth::user()->company_id;
        }

        if (!$this->clientService->checkCompany($clientId, $companyId)) {
            return false;
        }

        return true;
    }

    private function validPasswordClient($request)
    {
        $clientId = Arr::get($request, "client_id");
        $password =  Arr::get($request, "password");

        if (!$this->clientService->checkPassword($clientId, $password)) {
            return false;
        }

        return true;
    }

    private function addStatus($statuses, $response, $companyId)
    {
        $arrStatus = [];
        if ($statuses) {
            
            if (!$this->statusService->checkCompany($statuses, $companyId)) {
                return false;
            }

            $newStatus = [];
            $newStatus["sale_id"] = $response->id;
            $newStatus["status_id"] = $statuses;
            $newStatus['created_at'] = $this->carbon->now();
            array_push($arrStatus, $newStatus);

            $response->status()->attach(
                $arrStatus
            );
        }
        return $response;
    }


    private function addProducts($request, $response, $companyId)
    {
        $arrProducts = [];
        $products = Arr::get($request, "products", []);

        $amountTotal = 0;

        $responseProducts = [];
        $responseProducts['status'] = true;
        $responseProducts['message'] = '';
        $responseProducts['total'] = $amountTotal;
        
        $saleId = Arr::get($request, "id", false);
        
        foreach ($products as $product) {

            if (!$this->productService->checkCompany($product, false, $companyId)) {
                $responseProducts['message'] = "Um dos produtos não está em sua base. Tente novamente com um produto válido";
                $responseProducts['status'] = false;
                return $responseProducts;
            }

            if ($this->productService->checkCompany($product, false, $companyId)) {
                $quantity =  Arr::get(
                    $request,
                    "qtde$product",
                    1
                );

                $size =  Arr::get(
                    $request,
                    "size$product",
                    false
                );

                $productDB = $this->productService->find($product);
                $quantityProdDB = Arr::get($productDB, "quantity");

                if (Arr::get($productDB, "control_quantity") && !$saleId) {
                    if ((int) $quantityProdDB < (int) $quantity) {
                        $responseProducts['message'] = "Um dos produtos não possui a quantia em estoque solicitada. Tente novamente com uma quantia válida";
                        $responseProducts['status'] = false;
                        continue;
                    }

                    $productDB['quantity'] = $quantityProdDB - $quantity;
                    $this->productService->update(["id" => Arr::get($productDB, "id"), "quantity" => Arr::get($productDB, "quantity")], $companyId);
                }

                $saleValue = str_replace(",", '.', Arr::get($productDB, "sale_value"));

                $newProduct = [];
                $newProduct["product_id"] = $product;
                $newProduct["sale_id"] = $response->id;
                $newProduct["quantity"] = $quantity;

                if($size && $size > 0){
                    $newProduct["size_id"] = $size;
                }

                $amountTotal += $quantity * $saleValue;
                $newProduct["sale_value"] = $saleValue;
                $newProduct["created_at"] = $this->carbon->now();
                $newProduct["updated_at"] = $this->carbon->now();
                array_push($arrProducts, $newProduct);
            }
        }

        if (!$responseProducts['status']) {
            return $responseProducts;
        }

        $response->products()->attach(
            $arrProducts
        );

        $responseProducts['status'] = true;
        $responseProducts['total'] = $amountTotal;

        return $responseProducts;
    }

    /**
     * Remove specific task
     *
     * @param [type] $request
     * @return void
     */
    public function delete($request)
    {
        $saleId = Arr::get($request, "id");

        if (!$this->checkCompany($saleId)) {
            return response('Sem permissão para essa empresa', 422);
        }
        
        $msg = "Removido com sucesso";

        $sale = $this->repository->find($saleId);
        $products = $sale->products;
        foreach ($products as $product) {
            if (Arr::get($product, "control_quantity")) {
                $quantity = $product->pivot->quantity;
                $product['quantity'] = $product['quantity'] + $quantity;
                $this->productService->update(["id" => Arr::get($product, "id"), "quantity" => Arr::get($product, "quantity")],Arr::get($product, "company_id"));
                $msg .= " e estoque de produtos estornado";
            }
        }

        $response = $this->repository->delete($saleId);
        if ($response) {
            return response($msg, 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    private function checkCompany($saleId)
    {
        if ($saleId) {
            $companyId = Auth::user()->company_id;
            $companySale = $this->repository->find($saleId);

            if ($companyId != Arr::get($companySale, "company_id")) {
                return false;
            }
        }

        return true;
    }
}
