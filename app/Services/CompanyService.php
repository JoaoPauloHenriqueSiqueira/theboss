<?php

namespace App\Services;

use App\Library\Format;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CompanyService
{
    protected $repository;

    /**
     * Cria instancia de Serviço
     *
     * @return void
     */
    public function __construct(
        CompanyRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Pagina Usuários
     *
     * @return void
     */
    public function get()
    {
        return $this->repository->paginate(6);
    }

    public function find($userId)
    {
        return $this->repository->find($userId)->toArray();
    }

    public function findCompany($id)
    {
        return $this->repository->find($id);
    }

    public function isApi()
    {
        $company = $this->repository->find(Auth::user()->company_id);
        return $company->is_api;
    }


    public function updateConfigs($request)
    {
        $viewCalendar = 0;
        if ($request->has('view_calendar')) {
            $viewCalendar = 1;
        }
        $request['view_calendar'] = $viewCalendar;

        $controlSaleStatus = 0;
        if ($request->has('control_sale_status')) {
            $controlSaleStatus = 1;
        }
        $request['control_sale_status'] = $controlSaleStatus;

        $response = $this->repository->update($request->all(),Auth::user()->company_id);
        
        if ($response) {
            return redirect()->back()->with('message', 'Registro criado/atualizado!');
        }

        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }

    public function active($request)
    {
        $company = $this->repository->find(Auth::user()->company_id);

        $token = Format::extractNumbers($request->token);
        $companyToken = $company->token;

        $tokenDb = Format::extractNumbers($companyToken->token);
        
        if ($tokenDb == $token) {
            $company->active = 1;
            return $company->save();
        }

        return response('Token inválido, tente novamente', 422);
    }

    /**
     * Salva/atualiza registro no banco
     *
     * @param [type] $request
     * @return void
     */
    public function save($request)
    {
        $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request);
        if ($response) {
            return $response;
        }

        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }

    /**
     * Deleta usuário
     *
     * @param [type] $request
     * @return void
     */
    public function delete($request)
    {
        $userId = Arr::get($request, "id");
        $response = $this->repository->delete($userId);

        if ($response) {
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }
}
