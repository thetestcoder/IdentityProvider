<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\APIBaseController;
use App\Http\Requests\V1\ResetPasswordRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends APIBaseController
{
    public function reset(ResetPasswordRequest $request)
    {
        try {
            $response = Password::reset($request->only(
                'email', 'password', 'password_confirmation', 'token'
            ), function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            });

            if ($response === Password::PASSWORD_RESET) {
                return $this->successMessage( 'Password reset successful');
            } else {
                return $this->errorMessage('Password reset failed', 500);
            }
        }catch (\Exception $e) {
            Log::error($e);
            return $this->errorMessage( $e->getMessage(), $e->getCode());
        }
    }
}
