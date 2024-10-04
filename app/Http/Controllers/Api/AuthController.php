<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UserApiValidation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = UserApiValidation::loginValidation($request);

        if($validate['status'] == "error"){

            return response()->json(['status' => 'error','message' => $validate['message']],400);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {

            return response()->json(['status' => 'error','message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        $userType = $user->type === 1 ? 'admin' : 'user';

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'token_type' => 'bearer',
            'user_type' => $userType,
        ],200);

    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully']);
    }




}
