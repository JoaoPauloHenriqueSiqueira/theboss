<?php

namespace App\Http\Requests;

use App\Library\Format;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class Products extends FormRequest
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
        $valid = [
            'name' => 'required|min:1|max:255',
            'sale_value' => 'required',
            'cost_value' => 'required',
        ];

        $barCode = is_null($this->request->get('bar_code'));
        if (!$barCode) {
            $valid['bar_code'] = Rule::unique('products')->ignore($this->request->get('id'))->where(function ($query) {
                return $query->where('company_id', Auth::user()->company_id);
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
            'name.invalid' => "Apenas letras são aceitas",
            'name.min' => 'Mínimo de 1 letras para um nome',
            'name.max' => 'Máximo de 255 letras para um nome',
            'bar_code.min' => 'Mínimo de 3 letras para um código de barra',
            'bar_code.max' => 'Máximo de 255 letras para código de barra',
            'sale_value.required' => 'Valor de venda é um campo obrigatório',
            'cost_value.required' => 'Valor de custo é um campo obrigatório',
            'bar_code.unique' => "Já existe um produto com esse código de barras em sua base",
        ];
    }

    public function getValidatorInstance()
    {
        $this->extractNumbersValue();
        $this->verifyNotifiable();
        return parent::getValidatorInstance();
    }

    protected function extractNumbersValue()
    {
        if ($this->request->has('sale_value')) {
            $this->merge([
                'sale_value' => str_replace(",", '.', $this->request->get('sale_value'))
            ]);
        }

        if ($this->request->has('cost_value')) {
            $this->merge([
                'cost_value'
                =>
                str_replace(",", '.', $this->request->get('cost_value'))
            ]);
        }
    }

    protected function verifyNotifiable()
    {
        $notifiable = 0;
        $controlQuant = 0;
        $calcNotifyUser = 0;

        if ($this->request->has('notify')) {
            $notifiable = 1;
        }

        if ($this->request->has('control_quantity')) {
            $controlQuant = 1;
        }

        $this->merge([
            'notifiable' => $notifiable,
            'calc_notify_user' => $calcNotifyUser,
            'control_quantity' => $controlQuant
        ]);
    }
}
