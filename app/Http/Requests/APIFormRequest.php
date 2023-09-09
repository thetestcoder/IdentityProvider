<?php

namespace App\Http\Requests;

use App\Exceptions\Api422Exception;
use Illuminate\Foundation\Http\FormRequest;

abstract class APIFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws App\Exceptions\Api422Exception
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        throw new Api422Exception($errors);


    }
}
