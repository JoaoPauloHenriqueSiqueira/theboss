<?php

namespace App\Services;

use App\Library\Format;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Transformers\CategorieTransformer;
use App\Transformers\CategoryTransformer;
use App\Transformers\ProductTransformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Pagination\Paginator;

class CategoryService
{
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CategoryRepositoryInterface $repository,
        Carbon $carbon,
        ProductService $productService
    ) {
        $this->repository = $repository;
        $this->productService = $productService;
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
    public function get()
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });
        return $list->paginate(10);
    }

    public function list()
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];
        $list =  $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });
        
        return $list->get();
    }

    public function searchDate($dateFilter)
    {
        $dateFilter = $this->carbon->parse($dateFilter);
        $start = $dateFilter->copy()->startOfDay()->startOfMonth();
        $finish = $dateFilter->copy()->endOfDay()->endOfMonth();

        $filterColumns = ['company_id' => Auth::user()->company_id];
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns, $start, $finish) {
            return $query->whereBetween('created_at', [$start, $finish])->where($filterColumns);
        });

        return $list;
    }

    public function search($request)
    {
        $filterColumns = $this->makeParamsFilter($request);
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list->paginate(10);
    }

    private function makeParamsFilter($request)
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];

        if (Arr::get($request, 'search_name')) {
            array_push($filterColumns, ['name', 'like', '%' . Arr::get($request, 'search_name') . '%']);
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

    public function listProductsApi($request, $id)
    {
        $filterColumns = ['company_id' => $request->header('Company'), 'id' => $id];

        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns);
        });

        $page = $request->query('page');
        $perPage = $request->query('per_page');

        return Format::paginate($this->getPhotosProduct($list->all()), $perPage, $page);
    }

    public function getPhotosProduct($products)
    {
        if ($products->count() <= 0) {
            return $products;
        }

        foreach ($products as $p) {
            foreach ($p->products as &$k) {
                $k['photos'] = $this->productService->getPhotos($k);
            }
        }

        return (new ProductTransformer)->transform($products->first()->products);
    }

    public function listApi($request)
    {
        $filterColumns = ['company_id' => $request->header('Company')];

        $page = $request->query('page');
        $perPage = $request->query('per_page');

        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('name', 'ASC');
        });

        $list =  (new CategoryTransformer)->transform($list->all());
        return Format::paginate($list, $perPage, $page);
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

            $clientId = Arr::get($request, "id");
            if (!$this->checkCompany($clientId)) {
                return response('Sem permissão para essa empresa', 422);
            }

            $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());

            if ($response) {
                return redirect()->back()->with('message', 'Registro criado/atualizado!');
            }
        }
        return redirect()->back()->withInput($request->all())->with('message', 'Ocorreu algum erro');
    }

    /**
     * Remove specific task
     *
     * @param [type] $request
     * @return void
     */
    public function delete($request)
    {
        $clientId = Arr::get($request, "id");
        if (!$this->checkCompany($clientId)) {
            return response('Sem permissão para essa empresa', 422);
        }

        $response = $this->repository->delete($clientId);

        if ($response) {
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    private function checkCompany($clientId)
    {
        if ($clientId) {
            $companyId = Auth::user()->company_id;
            $client = $this->repository->find($clientId);

            if ($companyId != Arr::get($client, "company_id")) {
                return false;
            }
        }

        return true;
    }
}
