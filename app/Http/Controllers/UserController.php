<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Repository\UserRepo;
use Exception;

class UserController extends Controller
{
    public function checkUser(){
        $user = UserRepo::checkUser();
        if(empty($user)){
            return [
                'error' => true,
                'message' => "Voce nao esta logado na API, favor realizar o login",
                'id' => "{$user[0]['id']}",
                'name' => "{$user[0]['name']}",
                'email' => "{$user[0]['email']}"
            ];
        }
        return [
            'error' => false,
            'message' => "Voce esta logado na API, email: {$user[0]['email']}",
            'id' => "{$user[0]['id']}",
            'name' => "{$user[0]['name']}",
            'email' => "{$user[0]['email']}"
        ];
    }
}
