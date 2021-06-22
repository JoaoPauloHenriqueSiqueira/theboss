<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Role\AdminCheck;
use App\Role\CompanyCheck;
use App\Services\CompanyTokenActiveService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckActiveCompanyRole
 * @package App\Http\Middleware
 */
class CheckCompanyApiToken
{
    protected $companyTokenService;

    public function __construct(CompanyTokenActiveService $companyTokenService)
    {
        $this->companyTokenService = $companyTokenService;
    }
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */

        $apiToken = $request->header('Authorization');
        $company = $request->header('Company');
        //verificar company is api  

        $token = $this->companyTokenService->where(['api_token' => $apiToken, 'company_id' => $company]);
        if ($token->isEmpty()) {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST')
            ->header('Access-Control-Max-Age', '1000');
    }
}

