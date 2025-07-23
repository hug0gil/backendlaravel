<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{

    public function register(UserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            "name" => $validatedData["name"],
            "email" => $validatedData["email"],
            "password" => bcrypt($validatedData["password"]),
        ]);

        event(new UserRegistered($user));

        return response()->json(['message' => 'User created successfuly'], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $credentials = ['email' => $validatedData['email'], 'password' => $validatedData['password']];
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'User or password incorrect'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to generate authentication token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        //return response()->json(['token' => $token], Response::HTTP_CREATED);
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json(
            [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() // alternativa: config('jwt.ttl')
            ]
        );
    }

    public function who()
    {
        $user = JWTAuth::user();
        return response()->json($user);
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Logout successfuly!'], Response::HTTP_OK);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unable to log out, the token is invalid'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::getToken();
            $newToken = JWTAuth::refresh($token);
            // Con la blacklist activa, al hacer el refresh se invalida el antiguo, 
            // si no lo invalidamos manualmente y guardamos el renovado en una variable nueva
            return $this->respondWithToken($newToken);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unable to refresh the token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
