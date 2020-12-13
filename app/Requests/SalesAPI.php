<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesAPI extends FormRequest
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
            'products' => 'required',
            'user_id' => 'required'
        ];

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
}
