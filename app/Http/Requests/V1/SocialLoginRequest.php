<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\APIFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class SocialLoginRequest  extends APIFormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required_without:phone|nullable|email',
            'phone' => 'required_without:email|nullable|string',
            'agency_id' => 'nullable',
        ];
    }
}