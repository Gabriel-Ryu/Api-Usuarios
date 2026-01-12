<?php
namespace App\Repository;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;

/**
 * Class UserRepo
 * 
 * Repositório para armazenar queries do banco de dados usando o model User
 * 
 * @package App\Repository
 */
class UserRepo{
    /**
     * Cria usuários seguindo os parêmetros passados.
     * 
     * @param array ['name' => 'required|max:255', 'email' => 'required|max:255', 'password' => 'required|string|min:6']
     * @return @response 201 {
     *                      "error": false,
     *                      "message": "Usuário criado com sucesso",
     *                      "data": {"name": "Joao da Silva", "email": "joaosilva@email.com", "updated_at": "XXXX-XX-XXTXX:XX:XX.XXXXXXZ", "created_at": "XXXX-XX-XXTXX:XX:XX.XXXXXXZ", "id": 1}
     * }
     * @response 409 {
     *                      "error": true,
     *                      "message": "Este email ja esta em uso"
     * }
     * 
     * @throws \Throwable Caso haja falha na conexão com o banco.
     */
    public static function create($params){
        try {
            $user =  User::firstOrCreate($params);
            if (!$user->wasRecentlyCreated) {
                return response()->json([
                    'error' => true,
                    'message' => "Este email ja esta em uso",
                ], 409);
            }

            return response()->json([
                'error' => false,
                'message' => "Usuario criado com sucesso",
                'data' => $user
            ], 201);
        } catch (\Throwable $e) {
            $sqlState = $e->getCode();
            $driverCode = $e->errorInfo[1] ?? null;
            if ($sqlState === '23000' && $driverCode === 1062) {
                return response()->json([
                    'error' => true,
                    'message' => "Este email ja esta em uso",
                ], 409);
            }
            return response()->json([
                'error' => true,
                'message' => "Falha ao criar usuario",
                'detail'  => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verifica se o usuário existe, utilizando o redis para encontrar o usuário logado no momento.
     * 
     * @param null
     * @return 0 = ['id' => 1, 'name' => Joao da Silva, 'email' => joaosilva@email.com, 'password' => senhaHashada, 'created_at' => XXXX-XX-XX XX:XX:XX, 'updated_at' => XXXX-XX-XX XX:XX:XX]
     * 
     * @throws \Throwable Caso haja falha na conexão com o banco.
     */
    public static function checkUser(){
        $user = unserialize(Redis::get('user'));
        return User::where('id', $user)
        ->get()
        ->toArray();
    }
}