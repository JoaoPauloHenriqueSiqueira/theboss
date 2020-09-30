<?php

namespace App\Http\Controllers;;

use App\Http\Requests\Products;
use App\Services\CategoryService;
use App\Services\ClientService;
use App\Services\ProductService;
use App\Services\ProviderService;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $service;
    protected $categoryService;
    protected $providerService;

    /**
     * Construct function
     *
     * @param ClientService $service
     */
    public function __construct(
        ProductService $service,
        CategoryService $categoryService,
        ProviderService $providerService

    ) {
        $this->service = $service;
        $this->categoryService = $categoryService;
        $this->providerService = $providerService;
    }

    /**
     * Renderiza view com tasks
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $categories = $this->categoryService->get();
            $providers = $this->providerService->get();

            $pageConfigs = ['pageHeader' => true];
            return view('pages.products', 
            ["datas" => $this->service->get(), 
            "search" => [],
            "categories" => $categories,
            "providers" => $providers,
            'pageConfigs' => $pageConfigs], 
            ['breadcrumbs' => []]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function search(Request $request)
    {
        try {
            $categories = $this->categoryService->get();
            $pageConfigs = ['pageHeader' => true];
            return view('pages.products', [
                "datas" => $this->service->search($request), 
                "search" => $request->all(),
                "categories" => $categories,
                'pageConfigs' => $pageConfigs], 
                ['breadcrumbs' => []]);
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
    public function createOrUpdate(Products $request)
    {
        try {
            return $this->service->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
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
