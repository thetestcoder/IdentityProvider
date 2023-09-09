<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ChangePasswordRequest;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
      try {
          $user = new User([
              'name' => $request->name,
              'email' => $request->email,
              'password' => bcrypt($request->password),
              'site_url' => $request->site_url
          ]);
          $user->save();

          $token = $user->createToken(str_replace(" ", "", config('app.name')))->accessToken;

          return response()->json(['token' => $token], 201);
      }catch (\Exception $e){
          Log::error($e);
          return response()->json(['message' => 'Something went wrong!'], 500);
      }
    }

    public function login(LoginRequest $request)
    {
       try {
           if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
               $user = Auth::user();
               $token = $user->createToken(str_replace(" ", "", config('app.name')))->accessToken;
               return response()->json(['token' => $token]);
           } else {
               return response()->json(['error' => 'Unauthorized'], 401);
           }
       }catch (\Exception $e){
           Log::error($e);
           return response()->json(['message' => 'Something went wrong!'], 500);
       }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = Auth::user();

            if (!password_verify($request->old_password, $user->password)) {
                return response()->json(['error' => 'Incorrect old password'], 401);
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return response()->json(['message' => 'Password changed successfully']);
        }catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }
}
