<?php

namespace App\Http\Requests;

use App\Library\Format;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Category extends FormRequest
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
            'name' => 'required|min:1|max:255'
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
            'name.required' => "Nome é um campo obrigatório"
        ];
    }

    public function getValidatorInstance()
    {
      
        return parent::getValidatorInstance();
    }
}
