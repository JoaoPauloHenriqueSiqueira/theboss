<?php

namespace App\Services;

use App\Library\Format;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\SaleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class SaleService
{
    protected $repository;
    protected $productService;
    protected $clientService;
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
        Carbon $carbon
    ) {
        $this->repository = $repository;
        $this->productService = $productService;
        $this->clientService = $clientService;
        $this->statusService = $statusService;
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
        $list->sum('amount_total');
        return $list->paginate(10);
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
        });

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
        $filterColumns = ['company_id' => Auth::user()->company_id];

        if (Arr::get($request, 'search_client_id')) {
            array_push($filterColumns, ['client_id', '=',  Arr::get($request, 'search_client_id')]);
        }


        return  $filterColumns;
    }

    /**
     * FUnction to search a task
     *
     * @param [type] $taskId
     * @return void
     */
    public function find($taskId)
    {
        return $this->repository->find($taskId)->toArray();
    }

    /**
     * Save a task with a validation
     *
     * @param [type] $request
     * @return void
     */
    public function save($request)
    {
        if ($request->validated()) {
            $saleId = Arr::get($request, "id");
            $companyId = $request->header('Company');

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

            $amountTotal = $this->addProducts($request, $response, $companyId);

            if (!$amountTotal) {
                $response->delete();
                return redirect()->back()->with('message', 'Um dos produtos enviados, não está na sua base');
            }

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

    public function saveAPI($request)
    {
        if ($request->validated()) {
            $saleId = Arr::get($request, "id");
            $companyId = $request->header('Company');

            if (!$this->validClient($request)) {
                return response()->json(['message' => "Cliente não pertence à sua base"], 422);
            }

            if ($saleId) {
                $sale = $this->repository->find($saleId);
                $sale->products()->detach();
                $sale->status()->detach();
            }

            $request = $this->makeSale($request);
            $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());

            $amountTotal = $this->addProducts($request, $response, $companyId);

            if (!$amountTotal) {
                $response->delete();
                return response()->json(['message' => "Um dos produtos enviados, não está na sua base"], 405);
            }

            $response =  $this->repository->updateOrCreate(["id" => Arr::get($response, "id")], ['amount_total' => $amountTotal]);
            $statuses = Arr::get($request, "statuses", []);
            $responseStatus = $this->addStatus($statuses, $response, $companyId);

            if (!$responseStatus) {
                $response->delete();
                return response()->json(['message' => "Um dos status enviados, não está na sua base."], 405);
            }


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

        if (!$this->clientService->checkCompany($clientId, $companyId)) {
            return false;
        }

        return true;
    }

    private function addStatus($statuses, $response, $companyId)
    {
        $arrStatus = [];
        if ($statuses && count($statuses) > 0) {
            foreach ($statuses as $status) {
                if (!$this->statusService->checkCompany($status, $companyId)) {
                    return false;
                }

                $newStatus = [];
                $newStatus["sale_id"] = $response->id;
                $newStatus["status_id"] = $status;
                array_push($arrStatus, $newStatus);
            }

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
        foreach ($products as $product) {

            if (!$this->productService->checkCompany($product, false, $companyId)) {
                return false;
            }

            if ($this->productService->checkCompany($product, false, $companyId)) {
                $quantity =  Arr::get(
                    $request,
                    "qtde$product",
                    1
                );

                $productDB = $this->productService->find($product);
                $quantityProdDB = Arr::get($productDB, "quantity");
                if (Arr::get($productDB, "control_quantity")) {
                    if ($quantityProdDB < $quantity) {
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

                $amountTotal += $quantity * $saleValue;
                $newProduct["sale_value"] = $saleValue;
                $newProduct["created_at"] = $this->carbon->now();
                $newProduct["updated_at"] = $this->carbon->now();
                array_push($arrProducts, $newProduct);
            }
        }

        $response->products()->attach(
            $arrProducts
        );

        return $amountTotal;
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
                $this->productService->update(["id" => Arr::get($product, "id"), "quantity" => Arr::get($product, "quantity")]);
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
