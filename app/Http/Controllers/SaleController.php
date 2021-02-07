<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sales;
use App\Http\Requests\SalesAPI;
use App\Library\Format;
use App\Services\ClientService;
use App\Services\ProductService;
use App\Services\SaleService;
use App\Services\SizeService;
use App\Services\StatusService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    protected $productService;
    protected $clientService;
    protected $saleService;
    protected $sizeService;


    /**
     * Construct function
     *
     * @param ClientService $service
     */
    public function __construct(
        ProductService $productService,
        ClientService $clientService,
        SaleService $saleService,
        StatusService $statusService,
        SizeService $sizeService
    ) {
        $this->productService = $productService;
        $this->clientService = $clientService;
        $this->saleService = $saleService;
        $this->statusService = $statusService;
        $this->sizeService = $sizeService;
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
            $saleDate = Carbon::parse(Arr::get($request, "start", Carbon::now()));
            $saleDateEnd = Carbon::parse(Arr::get($request, "end", Carbon::now()));

            $saleDateFormat = Carbon::parse($saleDate->copy())->format('d/m/Y');
            $saleDateEndFormat = Carbon::parse($saleDateEnd->copy())->format('d/m/Y');


            $saleTitle = $saleDateFormat;


            if ($saleDate != $saleDateEnd) {
                $saleTitle = "$saleDateFormat - $saleDateEndFormat";
            }

            $clients = $this->clientService->list();
            $products = $this->productService->listFull();
            $sales = $this->saleService->searchBetweenDates($request, $saleDate, $saleDateEnd);

            $statuses = $this->statusService->list();
            $search = [];
            $search['start'] = $saleDate->format('Y-m-d');
            $search['end'] = $saleDateEnd->format('Y-m-d');
            $search['search_client_id'] = Arr::get($request, "search_client_id");

            $sizes = $this->sizeService->list();

            return view('pages.sales', [
                "search" => $search,
                "start" => $saleDate->format('Y-m-d'),
                "end" => $saleDateEnd->format('Y-m-d'),
                "sale_date_format" => $saleTitle,

                "datas" => $sales,
                "company"=> Auth::user()->company_id,
                "total_sales" => Format::money($sales->sum('amount_total')),
                "clients" => $clients,
                "statuses" => $statuses,
                "products" => $products,
                "sizes" => $sizes,
                'pageConfigs' => $pageConfigs
            ], ['breadcrumbs' => []]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function salesClientList(Request $request)
    {
        return $this->saleService->listClientApi($request);
    }

    /**
     * Cria ou atualiza dados no banco
     *
     * @param TaskPost $request
     * @return void
     */
    public function createOrUpdate(Sales $request)
    {
        try {
            return $this->saleService->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function createOrUpdateAPI(Sales $request)
    {
        try {
            return $this->saleService->saveAPI($request);
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
            return $this->saleService->delete($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
