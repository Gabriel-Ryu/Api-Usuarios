<?php
namespace App\Repository;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;

class UserRepo{
    public static function create($params){
        try {
            return User::firstOrCreate($params);
        } catch (\Throwable $e) {
            $sqlState = $e->getCode();
            $driverCode = $e->errorInfo[1] ?? null;
            if ($sqlState === '23000' && $driverCode === 1062) {
                return [
                    'message' => "Este email ja esta em uso",
                    'errors' => "Ja existe um usuario com este email",
                    'errorCode' => 422
                ];
            }
            return [
                'message' => "Falha ao criar usuario",
                'detail'  => $e->getMessage(),
                'errorCode' => 400
            ];

        }
    }

    public static function checkUser(){
        $user = unserialize(Redis::get('user'));
        return User::where('email', $user->email)
        ->get()
        ->toArray();
    }

    public static function getUser($email){
        return User::where('email', $email)
        ->first();
    }
}