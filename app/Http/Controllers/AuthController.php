<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages(),
                'errors' => [
                    'error_data' => $validator->errors(),
                    'error_code' => 'VALIDATION_ERROR',
                ]
            ]);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'errors' => [
                    'error_data' => 'Unauthorized',
                    'error_code' => 'UNAUTHORIZED',
                ]
            ], 401);
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages(),
                'errors' => [
                    'error_data' => $validator->errors(),
                    'error_code' => 'VALIDATION_ERROR',
                ]
            ]);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        $token = auth()->attempt($validator->validated());

        return $this->createNewToken($token);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'User successfully signed out'
        ]);
    }

    public function me()
    {
        return response()->json([
            'success' => true,
            'data' => auth()->user(),
        ]);
    }

    function createNewToken($token)
    {
        $cookie = cookie('token', $token, 60 * 24, null, null, false, true);
        return response()->json([
            'success' => true,
            'message' => 'Token generated successfully',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ])->withCookie($cookie);
    }
}
