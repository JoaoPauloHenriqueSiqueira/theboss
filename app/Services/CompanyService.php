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

    /**
     * Procura por usuário
     *
     * @param [type] $UserId
     * @return void
     */
    public function find($userId)
    {
        return $this->repository->find($userId)->toArray();
    }

    public function isApi()
    {
        $company = $this->repository->find(Auth::user()->company_id);
        return $company->is_api;
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
