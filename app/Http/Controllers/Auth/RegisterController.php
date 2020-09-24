<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRegister;
use App\Library\Format;
use App\Providers\RouteServiceProvider;
use App\Notifications\ActiveCompany;
use App\Services\CompanyService;
use App\Services\CompanyTokenActiveService;
use App\Services\UserService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    protected $service;
    protected $companyService;
    protected $companyTokenService;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserService $service,
        CompanyService $companyService,
        CompanyTokenActiveService $companyTokenService
    ) {
        $this->service = $service;
        $this->companyService = $companyService;
        $this->companyTokenService = $companyTokenService;
        $this->middleware('guest');
    }

    private function makeCompany($data)
    {
        $newData = [];
        $newData['name'] = Arr::get($data, 'company_name');
        $newData['cnpj'] = Format::extractNumbers(Arr::get($data, 'cnpj'));
        $newData['phone'] = Format::extractNumbers(Arr::get($data, 'phone'));
        $newData['email'] = Arr::get($data, 'email');
        $newData['active'] = 0;
        return $newData;
    }

    private function makeUser($data, $company)
    {
        $newData = [];
        $newData['name'] = Arr::get($data, 'username');
        $newData['email'] = Arr::get($data, 'email');
        $newData['company_id'] = $company->id;
        $newData['password'] = Hash::make(Arr::get($data, 'password'));
        return $newData;
    }

    private function makeToken($company)
    {
        $newData = [];
        $newData['attempts'] = 0;
        $newData['max_attempts'] = 6;
        $newData['company_id'] = $company->id;
        $newData['token'] = mt_rand(100000, 999999);
        return $newData;
    }

    public function showRegistrationForm()
    {
        $pageConfigs = ['bodyCustomClass' => 'register-bg', 'isCustomizer' => false];

        return view('/auth/register', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    public function register(CompanyRegister $request)
    {
        if ($request->validated()) {
            $company = $this->makeCompany($request);
            $companyData = $this->companyService->save($company);

            $user = $this->makeUser($request, $companyData);
            $user = $this->service->save($user, false);

            //gerar token 
            $token = $this->makeToken($companyData);
            $token = $this->companyTokenService->save($token);

            $user->notify(new ActiveCompany($user->name, $token->token));
            return (new LoginController)->login($request);
        }
        return redirect()->back()->withInput($request->all())->with('message', 'Ocorreu algum erro');
    }
}
