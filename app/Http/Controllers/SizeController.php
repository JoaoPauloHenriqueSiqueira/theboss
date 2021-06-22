<?php

namespace App\Http\Controllers;

use App\Http\Requests\Size;
use App\Services\SizeService;
use Exception;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    protected $service;

    /**
     * Construct function
     *
     * @param SizeService $service
     */
    public function __construct(SizeService $service)
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
            return view('pages.sizes', ["datas" => $this->service->search($request),"search"=>$request->all(), 'pageConfigs' => $pageConfigs], ['breadcrumbs' => []]);
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
    public function createOrUpdate(Size $request)
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
