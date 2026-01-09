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
                'message' => "You aren't signed, please sign in",
                'id' => "{$user[0]['id']}",
                'name' => "{$user[0]['name']}",
                'login' => "{$user[0]['login']}",
                'email' => "{$user[0]['email']}",
                'isAdm' => "{$user[0]['adm']}"
            ];
        }
        return [
            'error' => false,
            'message' => "User still already signed, login: {$user[0]['login']}",
            'id' => "{$user[0]['id']}",
            'name' => "{$user[0]['name']}",
            'login' => "{$user[0]['login']}",
            'email' => "{$user[0]['email']}",
            'isAdm' => "{$user[0]['adm']}"
        ];
    }

    public function create(CreateUserRequest $request){
        try {
            $validated = $request->validated();
            $user = UserRepo::create($validated);
            if($user['errorCode'] != 200){
                return $user;
            }
            return [
                'error' => false,
                'message' => "User successful created, id: {$user['id']}"
            ];
        } catch (\Throwable $th) {
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

    public function delete($id){
        $user = UserRepo::delete($id);
        return $user;
    }

    public function update(UpdateUserRequest $request,int $id){
        $validated = $request->validated();
        UserRepo::update($id, $validated);
        return [
            'error' => false,
            'message' => "User successful updated."
        ];
    }

    public function retore($id){
        
    }
}
