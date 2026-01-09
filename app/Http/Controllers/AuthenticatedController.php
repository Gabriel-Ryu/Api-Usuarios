<?php

namespace App\Http\Controllers;

use Exception;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Controllers\Controller;
use App\Repository\UserRepo;
use Illuminate\Support\Facades\Redis;

class AuthenticatedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(Request $request){

        $credentials = $request->only('login', 'password');
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Redis::set('user', serialize(auth('api')->user()));
        

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
        info('ent num responde');
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function register(UserRegisterRequest $request)
    {
        try{
            $validated = $request->validated();
            if($validated === false){
                throw new Exception('erro para registrar usuário');
            }
            $user = UserRepo::create($validated);
            info(print_r($user, true)); 
            if($user['errorCode'] != 200){
                return $user;
            }
            return [
                'error' => false,
                'message' => "User successful registered, id: $user"
            ];
        }
        catch (\Throwable $th) {
            \Log::error($th);

            $code = $th->getCode() >= 400 
                ? $th->getCode()
                : 500;

            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
            ], $code);
        }
    }
}
