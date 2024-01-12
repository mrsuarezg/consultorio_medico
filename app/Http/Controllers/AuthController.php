<?php

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class AuthController extends Controller
{
    public function __construct(){

    }

    public function login(Request $request): JsonResponse
    {
        $email = $request['email'];
        $password = $request['password'];
        $deviceName = $request['device_name'] ?? 'auth_token';
        if (false === Auth::attempt(['email' => $email, 'password' => $password])) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $user = User::where('email', $email)->firstOrFail();

        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
