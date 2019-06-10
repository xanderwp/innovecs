<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\User;

class AuthController extends Controller
{

    public function login(\App\Http\Requests\ApiLoginRequest $request)
    {

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => __('api.unauthorised')
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();
        $tokenResult = $user->createToken($user->id);
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return response()->json([
            'token_info' => [
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
                ]
        ], JsonResponse::HTTP_OK);

    }

    public function reg(\App\Http\Requests\ApiRegRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $tokenResult = $user->createToken($user->id);
        $token = $tokenResult->token;

        $token->save();

        return response()->json([
            'token_info' => [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            ]
        ], JsonResponse::HTTP_OK);

    }

    public function logout(\Illuminate\Http\Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
        ], JsonResponse::HTTP_OK);
    }
}
