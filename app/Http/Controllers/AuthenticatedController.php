<?php

namespace App\Http\Controllers;

use Exception;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Controllers\Controller;
use App\Repository\UserRepo;
use Illuminate\Support\Facades\Redis;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class AuthenticatedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(Request $request){

        $credentials = $request->only('email', 'password');
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Usuário desconhecido'], 401);
        }
        Redis::set('user', auth('api')->id());
        
        return $this->respondWithToken($token);
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
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        if($validated === false){
            throw new Exception('erro para registrar usuario');
        }
        $user = UserRepo::create($validated);
        if($user['errorCode']){
            return [
                'error' => true,
                'message' => $user['message']
            ];
        }
        Mail::to($user['email'])->send(new WelcomeEmail($user));

        return [
            'error' => false,
            'message' => "Usuario cadastrado com sucesso, id: {$user['id']}"
        ];
    }
}
