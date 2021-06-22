<?php

namespace App\Http\Requests;

use App\Library\Format;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Provider extends FormRequest
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
            'name' => 'required|min:3|max:255',
            'phone_number' => 'min:3|max:18',
            'email' => 'email'
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
            'name.required' => "Nome é um campo obrigatório",
            'name.min' => 'Mínimo de 3 letras para um nome',
            'name.max' => 'Máximo de 255 letras para um nome',
            'phone_number.min' => 'Mínimo de 3 dígitos para telefone',
            'email.email' => "Email precisa ser válido"
        ];
    }

    public function getValidatorInstance()
    {
        $this->extractNumbers();
        return parent::getValidatorInstance();
    }

    protected function extractNumbers()
    {
        if ($this->request->has('phone_number')) {
            $this->merge([
                'phone_number' => Format::extractNumbers($this->request->get('phone_number'))
            ]);
        }
    }

}
