<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
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
                return response()->json(['message' => 'Password reset successful']);
            } else {
                return response()->json(['error' => 'Password reset failed'], 500);
            }
        }catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }
}
