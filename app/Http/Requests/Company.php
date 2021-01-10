<?php

namespace App\Http\Requests;

use App\Library\Format;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Company extends FormRequest
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
            'phone' => 'min:3|max:255'
        ];

        $cpfCnpj = is_null($this->request->get('cnpj'));
        if (!$cpfCnpj) {
            $valid['cnpj'] = Rule::unique('companies')->ignore($this->request->get('id'))->where(function ($query) {
                return $query->where('id', Auth::user()->company_id);
            }) . "|min:11|max:14";
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
            'name.invalid' => "Apenas letras são aceitas",
            'cell_phone.required' => 'Celular é obrigatório',
            'name.min' => 'Mínimo de 3 letras para um nome',
            'name.max' => 'Máximo de 255 letras para um nome',
            'cpf_cnpj.min' => 'Mínimo de 11 dígitos para cpf/cnpj',
            'cpf_cnpj.max' => 'Máximo de 14 dígitos para cpf/cnpj',
            'cpf_cnpj.unique' => "Cliente com esse documento já está cadastrado em sua base",
            'type_id.exists' => 'Tipo de pessoa inválido',
        ];
    }

    public function getValidatorInstance()
    {
        $this->extractNumbers();
        $this->verifyType();
        $this->verifyNotifiable();
        return parent::getValidatorInstance();
    }

    protected function extractNumbers()
    {
        if ($this->request->has('cpf_cnpj')) {
            $this->merge([
                'cpf_cnpj' => Format::extractNumbers($this->request->get('cpf_cnpj'))
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
