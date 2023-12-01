<?php

namespace App\Repository\Contracts;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

interface AuthContract
{

    public function login(LoginRequest $request);

    public function register($request);

}
