<?php

namespace App\Services;

use App\Repositories\Contracts\CompanyTokenRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CompanyTokenActiveService
{
    protected $repository;

    /**
     * Cria instancia de Serviço
     *
     * @return void
     */
    public function __construct(
        CompanyTokenRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }
   
    public function find($userId)
    {
        return $this->repository->find($userId)->toArray();
    }

    public function where($search)
    {
       $list = $this->repository->scopeQuery(function ($query) use ($search) {
            return $query->where($search);
        });

        return $list->all();
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
}
