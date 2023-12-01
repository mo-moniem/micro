<?php

namespace App\Repository\Repos;

use App\Models\User;
use App\Repository\Contracts\AuthContract;
use Illuminate\Support\Facades\Auth;

class AuthRepo implements AuthContract
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function login($request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json(['status'=>false,'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $user['token'] = $token;
        return  $user;
    }


    public function register($data){
        $user = $this->model->create($data);
        return $user;
    }
}
