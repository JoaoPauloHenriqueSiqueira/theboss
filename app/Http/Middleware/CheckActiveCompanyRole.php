<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Role\AdminCheck;
use App\Role\CompanyCheck;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckActiveCompanyRole
 * @package App\Http\Middleware
 */
class CheckActiveCompanyRole
{
    /**
     * @var AdminCheck
     */
    protected $adminCheck;

    public function __construct(CompanyCheck $adminCheck)
    {
        $this->adminCheck = $adminCheck;
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

        $user = Auth::guard()->user();


        if (!$this->adminCheck->check($user)) {
            return redirect()->route('active', [$user]);
        }

        session(['company' => Auth::user()->company_id]);

        return $next($request);
    }
}
