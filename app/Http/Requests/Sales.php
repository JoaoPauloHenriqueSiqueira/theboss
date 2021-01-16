<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class Sales extends FormRequest
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
            'amount_paid' => 'required',
            'products' => 'required|array',
            'products.*.id' => 'exists:products,id',
            'statuses' => 'array|exists:statuses,id',

        ];

        $client = is_null($this->request->get('client_id'));
        if (!$client) {
            $valid['client_id'] = Rule::exists('clients', 'id');
        }

        $statuses = is_null($this->request->get('statuses'));
        if (!$statuses) {
            $valid['statuses.*.id'] = Rule::exists('statuses', 'id');
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
            'client_id.exists' => 'Cliente não encontrado na sua base',
            'amount_paid.required' => 'Valor pago é um campo obrigatório',
            'amount_total.required' => 'Valor total é um campo obrigatório',
            'products.required' => 'Nenhum produto adicionado',

        ];
    }

    public function getValidatorInstance()
    {
        $this->extractNumbersValue();
        return parent::getValidatorInstance();
    }

    protected function extractNumbersValue()
    {
        if ($this->request->has('amount_paid')) {
            $amount = $this->request->get('amount_paid');

            $amount = str_replace(",", '.', $amount);

            $this->merge([
                'amount_paid'
                =>
                $amount
            ]);
        }
    }
}
