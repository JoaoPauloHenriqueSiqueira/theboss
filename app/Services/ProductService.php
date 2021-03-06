<?php

namespace App\Services;

use App\Library\Format;
use App\Library\Upload;
use App\Models\Products;
use App\Repositories\Contracts\PhotoRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Transformers\ProductTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

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

    public function getSizes($request)
    {
        $id = Arr::get($request, 'id');
        return $this->repository->find($id)->sizes;
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

    public function listApi($request)
    {
        $filters = $this->makeParamsFilterAPI($request);

        $perPage = $request->query('per_page');

        $list = Products::where(Arr::get($filters, 0))->where(Arr::get($filters, 1))->orWhere(Arr::get($filters, 2))->where(Arr::get($filters, 0))->orderBy('name', 'DESC')->paginate($perPage);

        $items = (new ProductTransformer)->transform($list->items());
        $list->setCollection($items);
        return $list;
    }


    public function listApiCategory($request, $id)
    {
        $filters = $this->makeParamsFilterAPI($request);
        $perPage = $request->query('per_page');

        $list = Products::whereHas('categories', function ($query) use ($id, $filters) {
            $query
                ->where('category_id', '=', $id)
                ->where(Arr::get($filters, 0))
                ->where(Arr::get($filters, 1))
                ->orWhere(Arr::get($filters, 0))
                ->where(Arr::get($filters, 2))
                ->where('category_id', '=', $id);
        })->orderBy('name', 'DESC')->paginate($perPage);

        $items = (new ProductTransformer)->transform($list->items());
        $list->setCollection($items);
        return $list;
    }


    function makeParamsFilterAPI($request)
    {
        $filters = [];
        $filterColumns = ['company_id' => $request->header('Company')];

        $filterColumns2 = [
            'control_quantity' => 1,
        ];
        array_push($filterColumns2, ['quantity', '>', '0']);

        $filterColumns3 = [
            'control_quantity' => 0,

        ];
        $filters[0] = $filterColumns;
        $filters[1] = $filterColumns2;
        $filters[2] = $filterColumns3;

        return $filters;
    }

    public function getFull()
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list->paginate(10);
    }

    public function listFull()
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list->get();
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
        $companyId = $request->header('Company');
        if (!$companyId) {
            $companyId = Auth::user()->company_id;
        }
        $filterColumns = ['company_id' => $companyId];


        if (Arr::get($request, 'search_id')) {
            array_push($filterColumns, ['id', '=', Arr::get($request, 'search_id')]);
        }


        if (Arr::get($request, 'search_name')) {
            array_push($filterColumns, ['name', 'like', '%' . Arr::get($request, 'search_name') . '%']);
        }

        if (Arr::get($request, 'search_bar_code')) {
            array_push($filterColumns, ['bar_code', 'like', '%' . Arr::get($request, 'search_bar_code') . '%']);
        }

        if (Arr::get($request, 'search_cost_value')) {
            $costValue = Format::extractNumbers(Arr::get($request, 'search_cost_value'));
            array_push($filterColumns, ['cost_value', 'like', '%' .  $costValue . '%']);
        }

        if (Arr::get($request, 'search_sale_value')) {
            $saleValue = Format::extractNumbers(Arr::get($request, 'search_sale_value'));
            array_push($filterColumns, ['sale_value', 'like', '%' .  $saleValue . '%']);
        }

        if (Arr::get($request, 'search_quantity')) {
            $quantity = Format::extractNumbers(Arr::get($request, 'search_quantity'));
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
                return response('Sem permiss??o para essa empresa', 422);
            }

            if ($productId) {
                $product = $this->repository->find($productId);
                $product->categories()->detach();
                $product->providers()->detach();
                $product->sizes()->detach();
            }

            $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());

            $categories = Arr::get($request, "categories", []);
            $providers = Arr::get($request, "providers", []);
            $sizes = Arr::get($request, "sizes", []);

            $response = $this->addCategories($categories, $response);
            $response = $this->addProviders($providers, $response);
            $response = $this->addSizes($sizes, $response);

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

    private function addSizes($sizes, $response)
    {
        $arrSizes = [];
        if ($sizes && count($sizes) > 0) {
            foreach ($sizes as $size) {
                $newSize = [];
                $newSize["product_id"] = $response->id;
                $newSize["size_id"] = $size;
                array_push($arrSizes, $newSize);
            }

            $response->sizes()->attach(
                $arrSizes
            );
        }
        return $response;
    }


    public function update($request, $companyId)
    {
        $productId = Arr::get($request, "id");
        if (!$this->checkCompany($productId, false, $companyId)) {
            return response('Sem permiss??o para essa empresa', 422);
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
            return response('Sem permiss??o para essa empresa', 422);
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
            return response('Sem permiss??o para essa empresa', 422);
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
