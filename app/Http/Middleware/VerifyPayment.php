<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\CompanyService;

class VerifyPayment
{

    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $company = $this->companyService->findCompany(Auth::user()->company_id);
        $lastPayment = collect($company->payments)->sortByDesc('created_at')->first();

        $paymentDate = $company->created_at;

        if(!empty($lastPayment)){
            if($lastPayment->paid){
                $paymentDate = $lastPayment->created_at;
            }            
        }

        $hoje = Carbon::now();
        $nextPayment = Carbon::parse($paymentDate);

        if($nextPayment->diffInMonths($hoje) >= 1){
            return redirect()->route('payment');
        }

        return $next($request);
    }
}
