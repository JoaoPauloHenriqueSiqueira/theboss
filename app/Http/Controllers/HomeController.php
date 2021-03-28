<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyToken;
use App\Library\Format;
use App\Services\ClientService;
use App\Services\CompanyService;
use App\Services\SaleService;
use App\Services\SizeService;
use App\Services\StatusService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $clientService;
    protected $saleService;
    protected $companyService;
    protected $sizeService;
    protected $statusService;
    protected $carbon;

    /**
     * Construct function
     *
     * @param FlowService $service
     */
    public function __construct(
        ClientService $clientService,
        SaleService $saleService,
        CompanyService $companyService,
        SizeService $sizeService,
        StatusService $statusService,
        Carbon $carbon
    ) {
        $this->clientService = $clientService;
        $this->saleService = $saleService;
        $this->sizeService = $sizeService;
        $this->companyService = $companyService;
        $this->statusService = $statusService;
        $this->carbon = $carbon;
    }

    /**
     * Renderiza view com tasks
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $metrics = [];

            $date = $this->carbon::now();
            $lastDate = $date->copy()->subMonth();
            $yesterday = $date->copy()->subDay();


            $metrics['clients'] = $this->metricClients($date, $lastDate);
            $metrics['sales_month'] = $this->metricSalesMonth($date, $lastDate, true);
            $metrics['sales_today'] = $this->metricSalesMonth($date, $yesterday, false);
            $metrics['profit_today'] = $this->metricProfitSalesToday($date, $yesterday, false);

            $metrics['flows'] = 3;


            $sales = $this->saleService->getSalesByStatus($request);
            $sizes = $this->sizeService->list();
            $statuses = $this->statusService->list();

            return view('pages.home', [
                "metrics" => $metrics,
                "datas" => $sales,
                "sizes" => $sizes,
                'statuses' => $statuses

            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function active()
    {
        try {
            return view('auth.active');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function activePost(CompanyToken $request)
    {
        try {
            $name = Auth::user()->name;
            $this->companyService->active($request);
            return redirect()->route('home')->with("message", "Bem-vinda(o), $name  🖖");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function metricClients($date, $lastDate)
    {
        $clientSearch = $this->clientService->searchDate($date);
        $count = $clientSearch->count();
        $clientSearchLastM = $this->clientService->searchDate($lastDate);
        $count2 =  $clientSearchLastM->count();

        $metric = [];
        $metric['count'] = $count;

        if ($count && $count2 > 0) {
            $metric['porcent'] = $this->porcentagem($count, $count2) . "%";
        }

        return $metric;
    }
    

    private function metricSalesMonth($date, $lastDate, $isMonth)
    {
        $search1 = $this->saleService->searchDate($date, $isMonth);
        $count = $search1->sum('amount_total');

        $search2 = $this->saleService->searchDate($lastDate, $isMonth);
        $count2 =  $search2->sum('amount_total');

        $metric = [];
        $metric['count'] = Format::money($count);

        if ($count && $count2 > 0) {
            $metric['porcent'] = $this->porcentagem($count, $count2) . "%";
        }

        return $metric;
    }

    private function metricProfitSalesToday($date, $lastDate, $isMonth)
    {
        $search1 = $this->saleService->searchDate($date, $isMonth);
        $costTotal1 = 0;
        $saleTotal1 = 0;

        foreach ($search1  as $s1) {
            foreach ($s1->products as $p1) {
                $costTotal1 +=  str_replace(",", '.', $p1->cost_value);
                $saleTotal1 += str_replace(",", '.', $p1->sale_value);
            }
        }

        $profit1 = $saleTotal1 - $costTotal1;

        $search2 = $this->saleService->searchDate($lastDate, $isMonth);
        $costTotal2 = 0;
        $saleTotal2 = 0;

        foreach ($search2  as $s2) {
            foreach ($s2->products as $p2) {
                $costTotal2 +=  str_replace(",", '.', $p2->cost_value);
                $saleTotal2 += str_replace(",", '.', $p2->sale_value);
            }
        }

        $profit2 = $saleTotal2 - $costTotal2;


        $metric = [];
        $metric['count'] = Format::money($profit1);

        if ($profit1 && $profit2 > 0) {
            $metric['porcent'] = $this->porcentagem($profit1, $profit2) . "%";
        }

        return $metric;
    }



    public function porcentagem($parcial, $total)
    {

        if ($total == 0) {
            return number_format((($parcial - $total)) * 100, 0);
        }
        return number_format((($parcial - $total) / $total) * 100, 0);
    }
}
