<?php

namespace App\Http\Controllers\V1;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\APIBaseController;
use App\Http\Requests\UpdatePasswordByAdminRequest;
use App\Http\Requests\V1\ChangePasswordRequest;
use App\Http\Requests\V1\UpdatePasswordRequest;
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
                'email' => $request->email ?: null,
                'phone' => $request->phone ?: null,
                'password' => bcrypt($request->password),
                'signup_url' => $request->site_url,
                'is_user' => $request->is_user ?? 1,
                'agency_id' => $request->agency_id ?? 0,
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
           $user = User::where(function($q) use ($request){
                $q->where('email',$request->email);
                if($request->phone) {
                    $q->orWhere('phone', $request->phone);
                }
            })->where('agency_id', $request->agency_id ?? 0)->first();
           if(!$user){
               return $this->errorMessage('Unauthorized User', 422);
           }
           if (Auth::attempt(['agency_id' => $request->agency_id ?? 0, 'email' => $request->email, 'password' => $request->password])) {
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
                return $this->errorMessage('Incorrect old password',401);
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return $this->successMessage('Password changed successfully');
        }catch (\Exception $e) {
            Log::error($e);
            return $this->errorMessage($e->getMessage(), $e->getCode());
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = Auth::user();

            $user->password = bcrypt($request->new_password);
            $user->save();

            return $this->successMessage('Password changed successfully');
        }catch (\Exception $e) {
            Log::error($e);
            return $this->errorMessage($e->getMessage(), $e->getCode());
        }
    }

    public function updatePasswordByAdmin(UpdatePasswordByAdminRequest $request)
    {
        try {
            $admin_user = Auth::user();
            $user = User::where('agency_id' , $admin_user->agency_id)->where('email', $request->email)->first();

            if ($admin_user->is_user == UserTypeEnum::getValue("ADMIN")){
                if (!$user) {
                    throw new \Exception("User Not found");
                }
                $user->password = bcrypt($request->password);
                $user->save();
                return $this->successMessage('Password changed successfully');
            }else{
                throw new \Exception("Only admin user can change password");
            }
        }catch (\Exception $e) {
            Log::error($e);
            return $this->errorMessage($e->getMessage(), $e->getCode());
        }
    }

    public function attemptSocialLogin(SocialLoginRequest $request)
    {
        try {
            $user = User::where('agency_id',$request->agency_id ?? 0)->where(function($q) use ($request){
                $q->where('email',$request->email);
                if($request->phone) {
                    $q->orWhere('phone', $request->phone);
                }
            })->first();

            if(!$user){
                $name = $request->name;
                if(!$name) {
                    if($request->email) {
                        $name = $request->email;
                    }
                    else {
                        $name = $request->phone;
                    }
                }

                $user = new User;
                $user->email = $request->email ?: null;
                $user->phone = $request->phone ?: null;
                $user->name = $name;
                $user->agency_id = $request->agency_id ?? 0;
                $user->save();
            }


            $token = $user->createToken(str_replace(" ", "", config('app.name')))->accessToken;

            if($request->firebase_auth_id){
                $finduser = User::where('firebase_auth_id', $request->firebase_auth_id)->first();

                if(!$finduser){
                    $finduser = User::where('agency_id',$request->agency_id ?? 0)->where('email', $request->email)->first();

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
                            'name' => $request->name,
                            'agency_id' => $request->agency_id ?? 0
                        ]);
                    }
                }
            }


            return [
                'user' => isset($finduser) ? $finduser : $user,
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
