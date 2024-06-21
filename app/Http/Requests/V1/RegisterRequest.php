<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\APIFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest  extends APIFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,NULL,id,agency_id,'.request('agency_id',0),
            'password' => 'required|string|min:6|confirmed',
            'site_url' => 'string|nullable',
        ];
    }
}
