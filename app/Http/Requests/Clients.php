<?php

namespace App\Http\Requests;

use App\Library\Format;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Clients extends FormRequest
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
        $valid = [
            'name' => 'required|min:3|max:255',
            'cell_phone' => 'required|min:3|max:255'
        ];

        $companyId = app('request')->headers->get('Company');
        if (!$companyId) {
            $companyId = Auth::user()->company_id;
        }

        $cpfCnpj = is_null($this->request->get('cpf_cnpj'));
        if (!$cpfCnpj) {
            $valid['cpf_cnpj'] = Rule::unique('clients')->ignore($this->request->get('id'))->where(function ($query) use ($companyId) {
                return $query->where('company_id', $companyId);
            }) . "|min:11|max:14";
        }

        $cellPhone = is_null($this->request->get('cell_phone'));
        if (!$cellPhone) {
            $valid['cell_phone'] = Rule::unique('clients')->ignore($this->request->get('id'))->where(function ($query) use ($companyId) {
                return $query->where('company_id', $companyId);
            }) . "|required|min:8|max:20";
        }

        $email = is_null($this->request->get('email'));
        if (!$email) {
            $valid['email'] = Rule::unique('clients')->ignore($this->request->get('id'))->where(function ($query) use ($companyId) {
                return $query->where('company_id', $companyId);
            });
        }

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
            'name.invalid' => "Apenas letras s??o aceitas",
            'cell_phone.required' => 'Celular ?? obrigat??rio',
            'password.required' => 'Senha ?? obrigat??rio',
            'email.unique' => 'Voc?? j?? possui um cadastro com esse email',
            'cell_phone.unique' => 'Voc?? j?? possui um cadastro com esse n??mero de celular',
            'cell_phone.min' => 'O n??mero de celular precisa de ao menos 8 d??gitos',
            'name.min' => 'M??nimo de 3 letras para um nome',
            'name.max' => 'M??ximo de 255 letras para um nome',
            'cpf_cnpj.min' => 'M??nimo de 11 d??gitos para cpf/cnpj',
            'cpf_cnpj.max' => 'M??ximo de 14 d??gitos para cpf/cnpj',
            'cpf_cnpj.unique' => "Cliente com esse documento j?? est?? cadastrado em sua base"
        ];
    }

    public function getValidatorInstance()
    {
        $this->extractNumbers();
        return parent::getValidatorInstance();
    }

    protected function extractNumbers()
    {
        if ($this->request->has('cpf_cnpj')) {
            $this->merge([
                'cpf_cnpj' => Format::extractNumbers($this->request->get('cpf_cnpj'))
            ]);
        }

        if ($this->request->has('cep')) {
            $this->merge([
                'cep' => Format::extractNumbers($this->request->get('cep'))
            ]);
        }

        if ($this->request->has('cell_phone')) {
            $this->merge([
                'cell_phone' => Format::extractNumbers($this->request->get('cell_phone'))
            ]);
        }

        if ($this->request->has('phone')) {
            $this->merge([
                'phone' => Format::extractNumbers($this->request->get('phone'))
            ]);
        }

      
    }

    
}
