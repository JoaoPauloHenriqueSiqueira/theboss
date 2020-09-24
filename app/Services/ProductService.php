<?php

namespace App\Services;

use App\Library\Format;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class ProductService
{
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ProductRepositoryInterface $repository
    ) {
        $this->repository = $repository;
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

        foreach ($list as &$data) {
            $data['sale_value'] = Format::money($data['sale_value']);
            $data['cost_value'] = Format::money($data['cost_value']);
        }

        return $list->paginate(10);
    }

    public function getFull()
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list->paginate(10);
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

        if (Arr::get($request, 'name')) {
            array_push($filterColumns, ['name', 'like', '%' . Arr::get($request, 'name') . '%']);
        }

        if (Arr::get($request, 'bar_code')) {
            array_push($filterColumns, ['bar_code', 'like', '%' . Arr::get($request, 'bar_code') . '%']);
        }

        if (Arr::get($request, 'cost_value')) {
            $costValue = Format::extractNumbers(Arr::get($request, 'cost_value'));
            array_push($filterColumns, ['cost_value', 'like', '%' .  $costValue . '%']);
        }


        if (Arr::get($request, 'sale_value')) {
            $saleValue = Format::extractNumbers(Arr::get($request, 'sale_value'));
            array_push($filterColumns, ['sale_value', 'like', '%' .  $saleValue . '%']);
        }

        if (Arr::get($request, 'quantity')) {
            $quantity = Format::extractNumbers(Arr::get($request, 'quantity'));
            array_push($filterColumns, ['quantity', 'like', '%' .  $quantity . '%']);
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

            $productId = Arr::get($request, "id");
            if (!$this->checkCompany($productId)) {
                return response('Sem permissão para essa empresa', 422);
            }

            if ($productId) {
                $product = $this->repository->find($productId);
                $product->categories()->detach();
            }

            $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());

            $arrCategories = [];
            $categories = Arr::get($request, "categories", []);

            foreach ($categories as $category) {
                $newCategory = [];
                $newCategory["product_id"] = $response->id;
                $newCategory["category_id"] = $category;
                array_push($arrCategories, $newCategory);
            }

            $response->categories()->attach(
                $arrCategories
            );

            if ($response) {
                return redirect()->back()->with('message', 'Registro criado/atualizado!');
            }
        }
        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }

    public function update($request)
    {
        $productId = Arr::get($request, "id");
        if (!$this->checkCompany($productId)) {
            return response('Sem permissão para essa empresa', 422);
        }

        $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request);

        if ($response) {
            return redirect()->back()->with('message', 'Registro criado/atualizado!');
        }

        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }

    /**
     * Remove specific task
     *
     * @param [type] $request
     * @return void
     */
    public function delete($request)
    {
        $productId = Arr::get($request, "id");
        if (!$this->checkCompany($productId)) {
            return response('Sem permissão para essa empresa', 422);
        }

        $response = $this->repository->delete($productId);

        if ($response) {
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    public function checkCompany($productId)
    {
        if ($productId) {
            $companyId = Auth::user()->company_id;
            $client = $this->repository->find($productId);

            if ($companyId != Arr::get($client, "company_id")) {
                return false;
            }
        }

        return true;
    }
}
