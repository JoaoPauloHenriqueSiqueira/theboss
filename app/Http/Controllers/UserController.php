<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserValidator;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    /**
     * Construct function
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Renderiza view com usuarios
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $pageConfigs = ['pageHeader' => true];
            return view('pages.users', ["datas" => $this->service->get(), 'pageConfigs' => $pageConfigs], ['breadcrumbs' => []]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * Cria usuário
     *
     * @param UserValidator $request
     * @return void
     */
    public function create(UserValidator $request)
    {
        try {
            return $this->service->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Atualiza usuário no banco
     *
     * @param UserValidator $request
     * @return void
     */
    public function update(UserValidator $request)
    {
        try {
            return $this->service->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Deleta usuário no banco
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
