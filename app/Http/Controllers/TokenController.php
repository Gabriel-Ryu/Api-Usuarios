<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class TokenController extends Controller
{
    public function checkToken(){
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => true, 'message' => 'Token inválido'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => true, 'message' => 'Token expirado'], 403);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => 'Autorização do token não encontrada'], 403);
        }
    }
}
