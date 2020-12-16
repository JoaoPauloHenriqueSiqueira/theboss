<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sales;
use App\Http\Requests\SalesAPI;
use App\Library\Format;
use App\Services\ClientService;
use App\Services\ProductService;
use App\Services\SaleService;
use App\Services\StatusService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SaleController extends Controller
{
    protected $productService;
    protected $clientService;
    protected $saleService;


    /**
     * Construct function
     *
     * @param ClientService $service
     */
    public function __construct(
        ProductService $productService,
        ClientService $clientService,
        SaleService $saleService,
        StatusService $statusService
    ) {
        $this->productService = $productService;
        $this->clientService = $clientService;
        $this->saleService = $saleService;
        $this->statusService = $statusService;
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
            $saleDateStart = Arr::get($request, "sale_date_start", Carbon::now());
            $saleDateFormat = $saleDateStart->copy();
            $clients = $this->clientService->get();
            $products = $this->productService->getFull();
            $sales = $this->saleService->get($saleDateStart);
            $statuses = $this->statusService->get();
            $search = [];
            $search['sale_date_start'] = $saleDateStart->format('Y-m-d');
            $search['sale_date_end'] = $saleDateStart->format('Y-m-d');

            return view('pages.sales', [
                "search" => $search,
                "sale_date_start" => $saleDateStart->format('Y-m-d'),
                "sale_date_end" => $saleDateStart->format('Y-m-d'),
                "sale_date_format" => $saleDateFormat->format('d/m/Y'),
                "datas" => $sales->paginate(10),
                "total_sales" => Format::money($sales->sum('amount_total')),
                "clients" => $clients,
                "statuses" => $statuses,
                "products" => $products,
                'pageConfigs' => $pageConfigs
            ], ['breadcrumbs' => []]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function search(Request $request)
    {
        try {
            $pageConfigs = ['pageHeader' => true];
            $saleDate = Carbon::parse(Arr::get($request, "search_sale_date_start", Carbon::now()));
            $saleDateEnd = Carbon::parse(Arr::get($request, "search_sale_date_end", Carbon::now()));

            $saleDateFormat = Carbon::parse($saleDate->copy())->format('d/m/Y');
            $saleDateEndFormat = Carbon::parse($saleDateEnd->copy())->format('d/m/Y');

            $clients = $this->clientService->get();
            $products = $this->productService->getFull();
            $sales = $this->saleService->searchBetweenDates($request, $saleDate, $saleDateEnd);
            $saleTitle = $saleDateFormat;

            $statuses = $this->statusService->get();

            if ($saleDate != $saleDateEnd) {
                $saleTitle = "$saleDateFormat - $saleDateEndFormat";
            }


            $search = [];
            $search['sale_date_start'] = $saleDate->format('Y-m-d');
            $search['sale_date_end'] = $saleDateEnd->format('Y-m-d');
            $search['search_client_id'] = Arr::get($request, "search_client_id");

            return view('pages.sales', [
                "search" => $search,
                "sale_date_start" => $saleDate->format('Y-m-d'),
                "sale_date_end" => $saleDateEnd->format('Y-m-d'),
                "sale_date_format" => $saleTitle,
                "datas" => $sales->paginate(10),
                "total_sales" => Format::money($sales->sum('amount_total')),
                "clients" => $clients,
                "products" => $products,
                "statuses" => $statuses,
                'pageConfigs' => $pageConfigs
            ], ['breadcrumbs' => []]);
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
