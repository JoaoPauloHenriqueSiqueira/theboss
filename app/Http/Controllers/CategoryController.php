<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category;
use App\Services\CategoryService;
use App\Services\ClientService;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $service;

    /**
     * Construct function
     *
     * @param ClientService $service
     */
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Renderiza view com tasks
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $pageConfigs = ['pageHeader' => true];
            return view('pages.categories', ["datas" => $this->service->search($request), "search" => $request->all(), 'pageConfigs' => $pageConfigs], ['breadcrumbs' => []]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Cria ou atualiza dados no banco
     *
     * @param TaskPost $request
     * @return void
     */
    public function createOrUpdate(Category $request)
    {
        try {
            return $this->service->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getList(Request $request)
    {
        return $this->service->listApi($request);
    }


    public function getProducts(Request $request, $id)
    {
        $products = $this->service->listProductsApi($request, $id);
        return $products;
    }


    /**
     * Deleta dado do banco
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request)
    {
        try {
            return $this->service->delete($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
