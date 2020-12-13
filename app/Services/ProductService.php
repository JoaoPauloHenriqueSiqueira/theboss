<?php

namespace App\Services;

use App\Library\Format;
use App\Library\Upload;
use App\Repositories\Contracts\PhotoRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Transformers\ProductTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $repository;
    protected $photoRepository;
    public $uploadPlugin;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ProductRepositoryInterface $repository,
        PhotoRepositoryInterface $photoRepository,
        Upload $uploadPlugin

    ) {
        $this->repository = $repository;
        $this->photoRepository = $photoRepository;
        $this->uploadPlugin = $uploadPlugin;
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

    public function getPhotos($request)
    {

        $id = Arr::get($request, 'id');
        // $list = $this->repository->scopeQuery(function ($query) use ($id) {
        //     return $query->where('id', $id)->first()->pivot->get();
        // });
        return $this->repository->find($id)->photos;
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
                $product->providers()->detach();
            }

            $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());

            $categories = Arr::get($request, "categories", []);
            $providers = Arr::get($request, "providers", []);

            $response = $this->addCategories($categories, $response);
            $response = $this->addProviders($providers, $response);
            $response = $this->addPhotos($request, $response);

            if ($response) {
                return redirect()->back()->with('message', 'Registro criado/atualizado!');
            }
        }
        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }



    private function addPhotos($request, $response)
    {
        $fotos = $request->file('fotos');
        $arrFotos = [];
        $companyId = Auth::user()->company_id;
        $path = "photos/company/$companyId/product/$response->id";

        if ($path && $fotos) {
            foreach ($fotos as $foto) {
                $newPhoto = [];
                $pathPhoto = $this->uploadPlugin->upload($foto, $path);
                if (!$pathPhoto) {
                    return;
                }
                $photoId = $this->photoRepository->updateOrCreate(["id" => Arr::get($request, "id"), 'path' => $pathPhoto]);
                $newPhoto["photo_id"] = $photoId->id;
                $newPhoto["product_id"] = $response->id;
                array_push($arrFotos, $newPhoto);
            }

            $response->photos()->attach(
                $arrFotos
            );
        }
        return $response;
    }

    private function addCategories($categories, $response)
    {
        $arrCategories = [];
        if ($categories && count($categories) > 0) {
            foreach ($categories as $category) {
                $newCategory = [];
                $newCategory["product_id"] = $response->id;
                $newCategory["category_id"] = $category;
                array_push($arrCategories, $newCategory);
            }

            $response->categories()->attach(
                $arrCategories
            );
        }
        return $response;
    }

    private function addProviders($providers, $response)
    {
        $arrProviders = [];
        if ($providers && count($providers) > 0) {
            foreach ($providers as $provider) {
                $newProvider = [];
                $newProvider["product_id"] = $response->id;
                $newProvider["provider_id"] = $provider;
                array_push($arrProviders, $newProvider);
            }

            $response->providers()->attach(
                $arrProviders
            );
        }
        return $response;
    }


    public function update($request, $companyId)
    {
        $productId = Arr::get($request, "id");
        if (!$this->checkCompany($productId, false, $companyId)) {
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

    public function deletePhoto($request)
    {
        $photo = Arr::get($request, "id");
        $photo = $this->checkCompany($photo, true);
        if (!$photo) {
            return response('Sem permissão para essa empresa', 422);
        }

        $this->uploadPlugin->remove(Arr::get($photo, "path"));
        $response = $this->photoRepository->delete((Arr::get($photo, "id")));

        if ($response) {
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    public function checkCompany($productId, $photo = false, $companyId = false)
    {
        if ($productId) {
            if (!$companyId) {
                $companyId = Auth::user()->company_id;
            }

            if ($photo) {
                return $this->photoRepository->find($productId);
            }

            if (!$photo) {
                $product = $this->repository->where("id", $productId)->where("company_id", $companyId);

                if ($product->count() <= 0) {
                    return false;
                }
            }

            if ($companyId != Arr::get($product->first(), "company_id")) {
                return false;
            }
        }

        return true;
    }
}
