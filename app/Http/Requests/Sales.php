<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
            'products' => 'required|array',
            'products.*.id' => 'exists:products,id',
            'statuses' => 'exists:statuses,id',
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
            'amount_total.required' => 'Valor total é um campo obrigatório',
            'products.required' => 'Nenhum produto adicionado',
        ];
    }

}
