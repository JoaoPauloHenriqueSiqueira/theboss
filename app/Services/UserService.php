<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $repository;

    /**
     * Cria instancia de Serviço
     *
     * @return void
     */
    public function __construct(
        UserRepositoryInterface $repository
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

        if (Arr::get($request, 'email')) {
            array_push($filterColumns, ['email', 'like', '%' . Arr::get($request, 'email') . '%']);
        }

        return  $filterColumns;
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

    /**
     * Salva/atualiza registro no banco
     *
     * @param [type] $request
     * @return void
     */
    public function save($request,$valid = true)
    {
        if(!$valid){
            return $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request);
        }

        if ($request->validated()) {
            $request['company_id'] = Auth::user()->company_id;

            $update = Arr::get($request, "id", false);
            if ($update) {
                $request = $this->verifyUpdate($request, $this->find($update));
            }

            if (!$update) {
                $request['password'] = bcrypt(Arr::get($request, "password"));
            }

            $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());
            if ($response) {
                return redirect()->back()->with('message', 'Registro criado/atualizado!');
            }
        }

        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }

    /**
     * Remove password, caso atualização
     *
     * @param [type] $request
     * @return void
     */
    private function verifyUpdate($request)
    {
        unset($request['password']);
        return $request;
    }

    public function checkCompany($request)
    {

        $companyId = $request->header('Company');
        if (!$companyId) {
            $companyId = Auth::user()->company_id;
        }

        $userId = Arr::get($request, "user_id");
        if (!$userId) {
            $userId = Auth::user()->id;
        }

        if ($userId) {
            $client = $this->repository->find($userId);

            if ($companyId != Arr::get($client, "company_id")) {
                return false;
            }
        }

        return true;
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
