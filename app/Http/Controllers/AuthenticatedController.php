<?php

namespace App\Http\Controllers;

use Exception;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Controllers\Controller;
use App\Repository\UserRepo;
use Illuminate\Support\Facades\Redis;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

/**
 * @group Autenticação de Usuário
 *
 * APIs para gerenciar o acesso e registro de usuários.
 */
class AuthenticatedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Autenticar o usuário (login).
     * 
     * @bodyParam email string required Endereço de e-mail cadastrado. Example: joaosilva@email.com
     * @bodyParam password string required Senha do usuário cadastrado. Example: senha123
     * 
     * @response 200 {
     *  "access_token": "2|abc123...",
     *  "token_type": "Bearer",
     *  "expires_in": 3600
     * }
     * 
     * @response 401 {
     *  "error": "Usuário desconhecido"
     * }
     */
    public function login(UserLoginRequest $request){

        $credentials = $request->validated();
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Usuário desconhecido'], 401);
        }
        Redis::set('user', serialize(auth('api')->id()));
        
        return $this->respondWithToken($token);
    }

    /**
     * Obtém o token da API.
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

     /**
     * Registro de novo usuário.
     * 
     * @bodyParam name string required O nome do usuário. Example: Gabriel Ryu
     * @bodyParam email string required Endereço de e-mail único. Example: gabriel@email.com
     * @bodyParam password string required Senha de acesso (mínimo 6 caracteres). Example: senha123
     * 
     * @response 201 {
     *  "error": false,
     *  "message": "Usuário criado com sucesso",
     *  "data": {"name": "Joao da Silva", "email": "joaosilva@email.com", "updated_at": "XXXX-XX-XXTXX:XX:XX.XXXXXXZ", "created_at": "XXXX-XX-XXTXX:XX:XX.XXXXXXZ", "id": 1}
     * }
     */
    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        if($validated === false){
            throw new Exception('erro para registrar usuario');
        }
        $return = UserRepo::create($validated);
        if($return->getStatusCode() == 201){
            Mail::to(json_decode($return->getContent())->data->email)->send(new WelcomeEmail(json_decode($return->getContent())->data));
        }

        return $return;
    }
}
