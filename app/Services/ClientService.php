<?php

namespace App\Services;

use App\Library\Format;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

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

    public function saveAPI($request)
    {
        $clientId = Arr::get($request, "id");
        $companyId = $request->header('Company');

        if (!$this->checkCompany($clientId, $companyId)) {
            return response('Sem permissão para essa empresa', 422);
        }

        $request['company_id'] = $companyId;

        $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());

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
}
