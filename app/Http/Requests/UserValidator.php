<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserValidator extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST': {
                    return [
                        'name' => 'required|max:255',
                        'email' => 'required|unique:users,email|max:255',
                        'password' => 'required|min:8|max:255',
                    ];
                }
            case 'PUT': {
                    return [
                        'name' => 'required|max:255',
                        'email' => 'required|email|unique:users,id,:id',
                    ];
                }
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é um campo necessário',
            'email.required' => 'Email é um campo necessário',
            'email.unique' => 'Email já está sendo utilizado',
            'password.min' => 'Senha precisa de 8 caracteres no mínimo',
            'password.required' => 'Senha é um campo necessário',
        ];
    }
}
