<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Repository\UserRepo;
use Exception;

/**
 * @group Ações do usuário
 * 
 */
class UserController extends Controller
{
    /**
     * Apresenta o usuário logado atualmente
     * 
     * @response 200 {
     *  "data": [{"id": 1, "nome": "Teclado Mecânico", "preco": 250.00}]
     * }
     */
    /**
     * Apresenta o usuário logado atualmente.
     * 
     * @bodyParam email string required Endereço de e-mail cadastrado. Example: joaosilva@email.com
     * @bodyParam password string required Senha do usuário cadastrado. Example: senha123
     * 
     * @response 200 {
     *  "error": false,
     *  "message": "Voce nao esta logado na API, favor realizar o login",
     *  "id": 1,
     *  "name": "Joao da Silva"
     *  "email": "joaosilva@email.com"
     * }
     * 
     * @response 401 {
     *  "error": true,
     *  "message": "Voce nao esta logado na API, favor realizar o login",
     *  "id": 1,
     *  "name": "Joao da Silva"
     *  "email": "joaosilva@email.com"
     * }
     */
    public function checkUser(){
        $user = UserRepo::checkUser();
        if(empty($user)){
            return response()->json([
                'error' => true,
                'message' => "Voce nao esta logado na API, favor realizar o login",
                'id' => "{$user[0]['id']}",
                'name' => "{$user[0]['name']}",
                'email' => "{$user[0]['email']}"
            ], 401);
        }
        return response()->json([
            'error' => false,
            'message' => "Voce esta logado na API, email: {$user[0]['email']}",
            'id' => "{$user[0]['id']}",
            'name' => "{$user[0]['name']}",
            'email' => "{$user[0]['email']}"
        ], 200);
    }
}
