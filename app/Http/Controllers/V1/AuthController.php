<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\APIBaseController;
use App\Http\Requests\V1\ChangePasswordRequest;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends APIBaseController
{
    public function register(RegisterRequest $request)
    {
      try {
          $user = new User([
              'name' => $request->name,
              'email' => $request->email,
              'password' => bcrypt($request->password),
              'signup_url' => $request->site_url
          ]);
          $user->save();

          $token = $user->createToken(str_replace(" ", "", config('app.name')))->accessToken;

          return $this->successMessage("Registered Successfully",  ['token' => $token]);
      }catch (\Exception $e){
          Log::error($e);
          return $this->errorMessage( $e->getMessage());
      }
    }

    public function login(LoginRequest $request)
    {
       try {
           if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
               $user = Auth::user();
               $token = $user->createToken(str_replace(" ", "", config('app.name')))->accessToken;
               return $this->successMessage("Login Successfully", ['token' => $token]);
           } else {
               return $this->errorMessage( 'Unauthorized',400);
           }
       }catch (\Exception $e){
           Log::error($e);
           return $this->errorMessage( $e->getMessage(), $e->getCode());
       }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = Auth::user();

            if (!password_verify($request->old_password, $user->password)) {
                return $this->errorMessage(  'Incorrect old password',401);
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return $this->successMessage('Password changed successfully');
        }catch (\Exception $e) {
            Log::error($e);
            return $this->errorMessage($e->getMessage(), $e->getCode());
        }
    }

    public function checkAuth()
    {
        return $this->successMessage('Authenticated');
    }
}
