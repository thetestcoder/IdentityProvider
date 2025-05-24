<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\APIFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest  extends APIFormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required_without:phone',
                'nullable',
                'email',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('agency_id', request('agency_id', 0));
                }),
            ],

            'phone' => [
                'required_without:email',
                'nullable',
                'string',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('agency_id', request('agency_id', 0));
                }),
            ],
            'password' => 'required|string',
        ];
    }
}
