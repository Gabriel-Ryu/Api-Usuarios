<?php
namespace App\Repository;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;

class UserRepo{
    public static function create($params){
        try {
            info(print_r($params, true));
            return User::firstOrCreate($params);
        } catch (\Throwable $e) {
            $sqlState = $e->getCode();
            $driverCode = $e->errorInfo[1] ?? null;
            if ($sqlState === '23000' && $driverCode === 1062) {
                return [
                    'message' => "These email or login it's already in use",
                    'errors' => ['email' => ["A user with this email or login already exists."]],
                    'errorCode' => 422
                ];
            }
            return [
                'message' => "Failed to create user.",
                'detail'  => $e->getMessage(),
                'errorCode' => 400
            ];

        }
    }

    public static function checkUser(){
        $user = unserialize(Redis::get('user'));
        return User::where('login', $user->login)
        ->get()
        ->toArray();
    }

    public static function checkAdm(){
        $user = unserialize(Redis::get('user'));
        return [Gate::authorize('access-admin', $user), $user['login']];
    }

    public static function delete($id){
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return [
                'error' => false,
                'message' => "User successful deleted."
            ];
        } catch (\Throwable $e) {
            return [
                'message' => "User not found",
                'errorCode' => 404
            ];
        } 
    }

    public static function update($id, $params){
        $user = User::where('id', $id)
        ->first();
        info(print_r($params, true));
        $user->update($params);
    }
}