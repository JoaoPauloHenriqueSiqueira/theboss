<?php

namespace App\Http\Requests;

use App\Library\Format;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CompanyRegister extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $valid['email'] =  'required|unique:users,email|max:255';
        $valid['password'] = 'required|min:8|confirmed';
        $cpfCnpj = is_null($this->request->get('cnpj'));
        if (!$cpfCnpj) {
            $valid['cnpj'] = Rule::unique('companies')->ignore($this->request->get('id'))->where(function ($query) {
                return $query->where('id', Auth::user()->company_id);
            }) . "|min:11|max:14";
        }

        return $valid;
    }


    public function getValidatorInstance()
    {
        $this->extractNumbers();
        return parent::getValidatorInstance();
    }

    protected function extractNumbers()
    {
        if ($this->request->has('cnpj')) {
            $this->merge([
                'cnpj' => Format::extractNumbers($this->request->get('cnpj'))
            ]);
        }

        if ($this->request->has('phone')) {
            $this->merge([
                'phone' => Format::extractNumbers($this->request->get('phone'))
            ]);
        }
    }

    /**
     * Get the error messages that apply to the request parameters.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'cnpj.unique' => "Essa empresa já está cadastrada em nossa base",
            'email.unique' => "Esse email já está cadastrado em nossa base",
            'password.min' => "São necessários 8 caracteres para o campo senha",
            'password.max' => "São necessários 8 caracteres para o campo senha",
            'password.confirmed' => "\"Senha\" e \"Confirmação de Senha\" não são iguais",
        ];
    }
}
