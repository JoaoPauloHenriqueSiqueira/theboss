<?php

namespace App\Services;

use App\Library\Format;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Transformers\ClientTransformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class ClientService
{
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ClientRepositoryInterface $repository,
        Carbon $carbon
    ) {
        $this->repository = $repository;
        $this->carbon = $carbon;
    }

    /**
     * All repository
     */
    public function all()
    {
        return $this->repository->orderBy('name')->get();
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
        return $list->paginate(10);
    }

    public function list()
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });
        return $list->get();
    }

    public function searchDate($dateFilter)
    {
        $dateFilter = $this->carbon->parse($dateFilter);
        $start = $dateFilter->copy()->startOfDay()->startOfMonth();
        $finish = $dateFilter->copy()->endOfDay()->endOfMonth();


        $filterColumns = ['company_id' => Auth::user()->company_id];
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns, $start, $finish) {
            return $query->whereBetween('created_at', [$start, $finish])->where($filterColumns);
        });

        return $list;
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

        if (Arr::get($request, 'search_name')) {
            array_push($filterColumns, ['name', 'like', '%' . Arr::get($request, 'search_name') . '%']);
        }

        if (Arr::get($request, 'search_phone')) {
            $phone = Format::extractNumbers(Arr::get($request, 'search_phone'));
            array_push($filterColumns, ['phone', 'like', '%' .  $phone . '%']);
        }

        if (Arr::get($request, 'search_cell_phone')) {
            $cellPhone = Format::extractNumbers(Arr::get($request, 'search_cell_phone'));
            array_push($filterColumns, ['cell_phone', 'like', '%' .  $cellPhone . '%']);
        }

        if (Arr::get($request, 'search_email')) {
            array_push($filterColumns, ['email', 'like', '%' . Arr::get($request, 'search_email') . '%']);
        }

        if (Arr::get($request, 'search_cpf_cnpj')) {
            $cpfCnpj = Format::extractNumbers(Arr::get($request, 'search_cpf_cnpj'));
            array_push($filterColumns, ['cpf_cnpj', 'like', '%' .  $cpfCnpj . '%']);
        }

        if (Arr::get($request, 'search_address')) {
            $address = Format::extractNumbers(Arr::get($request, 'search_address'));
            array_push($filterColumns, ['address', 'like', '%' .  $address . '%']);
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

            $clientId = Arr::get($request, "id");
            if (!$this->checkCompany($clientId)) {
                return response('Sem permissão para essa empresa', 422);
            }
            $request['company_id'] = Auth::user()->company_id;
            $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());

            if ($response) {
                return redirect()->back()->with('message', 'Registro criado/atualizado!');
            }
        }
        return redirect()->back()->withInput($request->all())->with('message', 'Ocorreu algum erro');
    }

    public function findClientPasswordMail($request)
    {
        $filterColumns = $this->makeParamsFilterAPI($request);
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns);
        });

        $user = $list->first();
        if (Hash::check(Arr::get($request, "password"), Arr::get($user, "password"))) {
            return $user;
        }

        return false;
    }

    public function findAPI($request)
    {
        $user = $this->findClientPasswordMail($request);

        if (Hash::check(Arr::get($request, "password"), Arr::get($user, "password"))) {
            return response()->json(['message' => "Login realizado com sucesso", "client" => (new ClientTransformer)->transform($user)], 201);
        }

        return response()->json(['message' => "Usuário/senha não conferem"], 500);
    }

    public function makeParamsFilterAPI($request)
    {
        $filterColumns = [
            'company_id' => $request->header('Company'),
        ];

        if (Arr::get($request, 'email')) {
            array_push($filterColumns, ['email', '=',  Arr::get($request, 'email')]);
        }

        if (Arr::get($request, 'id')) {
            array_push($filterColumns, ['id', '=',  Arr::get($request, 'id')]);
        }

        return $filterColumns;
    }

    private function verifyUpdate($request)
    {
        unset($request['password']);
        return $request;
    }

    public function saveAPI($request)
    {
        $clientId = Arr::get($request, "id");
        $companyId = $request->header('Company');
        if (!$this->checkCompany($clientId, $companyId)) {
            return response()->json(['message' => "Sem permissão para essa empresa"], 422);
        }

        $request['company_id'] = $companyId;

        $id = Arr::get($request, "id");
        if ($id && !$this->checkPassword($id,  Arr::get($request, "password"))) {
            return response()->json(['message' => "Senha errada para alterar esse usuário"], 422);
        }

        $request['password'] = bcrypt(Arr::get($request, "password"));

        if ($id) {
            $request = $this->verifyUpdate($request);
        }


        $response = $this->repository->updateOrCreate(["id" => $id], $request->all());

        if ($response) {
            return response()->json(['message' => "Registro criado/atualizado!", "id" => Arr::get($response, "id")], 201);
        }

        return response()->json(['message' => "Ocorreu um erro"], 500);
    }


    /**
     * Remove specific task
     *
     * @param [type] $request
     * @return void
     */
    public function delete($request)
    {
        $clientId = Arr::get($request, "id");
        if (!$this->checkCompany($clientId)) {
            return response('Sem permissão para essa empresa', 422);
        }

        $response = $this->repository->delete($clientId);

        if ($response) {
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    public function checkCompany($clientId,  $companyId = false)
    {
        if (!$companyId) {
            $companyId = Auth::user()->company_id;
        }

        if ($clientId) {
            $client = $this->repository->find($clientId);

            if ($companyId != Arr::get($client, "company_id")) {
                return false;
            }
        }

        return true;
    }

    public function checkPassword($clientId,  $password)
    {
        if ($clientId) {
            $client = $this->repository->find($clientId);
            if (Hash::check($password, Arr::get($client, "password"))) {
                return true;
            }
        }
        return false;
    }
}
