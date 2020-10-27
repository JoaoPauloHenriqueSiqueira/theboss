<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category;
use App\Http\Requests\Clients;
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
    public function index()
    {
        try {
            $pageConfigs = ['pageHeader' => true];
            return view('pages.categories', ["datas" => $this->service->get(),"search"=>[], 'pageConfigs' => $pageConfigs], ['breadcrumbs' => []]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function search(Request $request)
    {
        try {
            $pageConfigs = ['pageHeader' => true];
            return view('pages.categories', ["datas" => $this->service->search($request),"search"=>$request->all(), 'pageConfigs' => $pageConfigs], ['breadcrumbs' => []])->withInput($request->all());
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
