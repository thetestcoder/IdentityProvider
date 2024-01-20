<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\APIBaseController;
use App\Http\Requests\V1\ChangePasswordRequest;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Requests\V1\SocialLoginRequest;
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
                'signup_url' => $request->site_url,
                'is_user' => $request->is_user ?? 1;
            ]);
            $user->save();

            $token = $user->createToken(str_replace(" ", "", config('app.name')))->accessToken;

            return $this->successMessage('Token generated successfully', ['token' => $token],200);
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

               return $this->successMessage('Token generated successfully', ['token' => $token],200);
           } else {
               return $this->errorMessage( 'Unauthorized',401);
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

    public function attemptSocialLogin(SocialLoginRequest $request)
    {
        try {
            $user = User::firstOrCreate(['email' => $request->email, 'name' => $request->name]);
            $token = $user->createToken(str_replace(" ", "", config('app.name')))->accessToken;

            $finduser = User::where('firebase_auth_id', $request->firebase_auth_id)->first();

            if(!$finduser){
                $finduser = User::where('email', $request->email)->first();

                if(!empty($finduser)) {
                    if($finduser->firebase_auth_id != $request->firebase_auth_id)
                    {
                        // Update firebase social login id
                        $finduser = User::where('id',$finduser->id)->update([
                            'firebase_auth_id' => $request->firebase_auth_id
                        ]);
                    }
                } else {
                    $finduser = User::create([
                        'email' => $request->email,
                        'firebase_auth_id' => $request->firebase_auth_id,
                        'name' => $request->name
                    ]);
                }
            }
            return [
                'user' => $finduser,
                'access_token' => $token
            ];
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorMessage($e->getMessage(), $e->getCode());
        }
    }

    public function me()
    {
        try {
            return $this->successMessage('Data retrieved successfully', Auth::user());
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
