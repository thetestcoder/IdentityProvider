<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\APIBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ResetPasswordEmailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends APIBaseController
{
    public function sendResetLinkEmail(ResetPasswordEmailRequest $request)
    {
        try {
            $response = Password::sendResetLink($request->only('email'));
            if ($response === Password::RESET_LINK_SENT) {
                return $this->successMessage( 'Password reset email sent');
            } else {
                return $this->errorMessage('Unable to send password reset email', 500);
            }
        }catch (\Exception $e) {
            Log::error($e);
            return $this->errorMessage( $e->getMessage(), $e->getCode());
        }
    }
}
