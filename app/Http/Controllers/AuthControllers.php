<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllers extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        try {
            $user = User::where('username', request('username'))->where('status', 1)->first();
            if (!$user) {
                return response()->json(['error' => 'Akun tidak aktif'], 500);
            }

            if (!($token = JWTAuth::attempt($credentials))) {
                return response()->json(['error' => 'Username atau Password Salah'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user->update([
            'api_token' => $token,
        ]);

        $logController = app(LogControllers::class);
        $logController->addToLog('Login user ' . $request->username);

        return response()->json([
            'status' => 200,
            'user' => $user,
            'token' => $token,
        ]);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Berhasil Keluar, Sampai jumpa']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() + 60 * 60 * 60 * 24,
        ]);
    }
}
