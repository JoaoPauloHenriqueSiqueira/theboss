<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sales;
use App\Library\Format;
use App\Services\ClientService;
use App\Services\ProductService;
use App\Services\SaleService;
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
        SaleService $saleService
    ) {
        $this->productService = $productService;
        $this->clientService = $clientService;
        $this->saleService = $saleService;
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
            $saleDate = Arr::get($request, "sale_date", Carbon::now());
            $saleDateFormat = $saleDate->copy();
            $saleTime = Arr::get($request, "sale_date", Carbon::now()->format('H:i'));
            $clients = $this->clientService->get();
            $products = $this->productService->getFull();
            $sales = $this->saleService->get($saleDate);

            return view('pages.sales', [
                "search" => [],
                "sale_date" => $saleDate->format('Y-m-d'),
                "sale_date_format" => $saleDateFormat->format('d/m/Y'),
                "sale_time" => $saleTime,
                "datas" => $sales->paginate(10),
                "total_sales" => Format::money($sales->sum('amount_total')),
                "clients" => $clients,
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
            $saleDate = Carbon::parse(Arr::get($request, "search_sale_date", Carbon::now()));
            $saleDateFormat = $saleDate->copy();
            $saleTime = Arr::get($request, "sale_date", Carbon::now()->format('H:i'));
            $clients = $this->clientService->get();
            $products = $this->productService->getFull();
            $sales = $this->saleService->search($saleDate);

            return view('pages.sales', [
                "search" => $request->all(),
                "sale_date" => $saleDate->format('Y-m-d'),
                "sale_date_format" => $saleDateFormat->format('d/m/Y'),
                "sale_time" => $saleTime,
                "datas" => $sales->paginate(10),
                "total_sales" => Format::money($sales->sum('amount_total')),
                "clients" => $clients,
                "products" => $products,
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
