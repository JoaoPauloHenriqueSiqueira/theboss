<?php

namespace App\Http\Requests;

use App\Library\Format;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CompanyToken extends FormRequest
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
        $valid = [];

        $valid['token'] = Rule::exists('company_token_actives','token')->where(function ($query) {
            return $query->where('company_id', Auth::user()->company_id);
        });

        return $valid;
    }

    /**
     * Get the error messages that apply to the request parameters.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'token.invalid' => "Token inválido, tente novamente",
            'token.exists' => "Token inválido, tente novamente"

        ];
    }

    public function getValidatorInstance()
    {
        $this->extractNumbers();
        return parent::getValidatorInstance();
    }

    protected function extractNumbers()
    {
        if ($this->request->has('token')) {
            $this->merge([
                'token' => Format::extractNumbers($this->request->get('token'))
            ]);
        }
    }

    protected function verifyType()
    {
        if ($this->request->has('cpf_cnpj')) {

            $count = strlen($this->request->get('cpf_cnpj'));

            $type = 2;
            if ($count > 11) {
                $type = 1;
            }

            $this->merge([
                'type_id' => $type
            ]);
        }
    }

    protected function verifyNotifiable()
    {
        $notifiable = 0;

        if ($this->request->has('notify')) {
            $notifiable = 1;
        }

        $this->merge([
            'notifiable' => $notifiable
        ]);
    }
}
