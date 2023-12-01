<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Repository\Contracts\AuthContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Services\RabbitMQService;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    /**
     * @param RabbitMQService $rabbitMQService
     * @param AuthContract $repo
     */
    public function __construct(protected RabbitMQService $rabbitMQService , protected AuthContract $repo)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','dummyUser']]);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request){
        $user = $this->repo->login($request);
        $this->rabbitMQService->publish($user['token']);
        return response()->json([
            'user' => $user,
        ]);

    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request){
        $user = $this->repo->register($request->validated());
        $this->rabbitMQService->publish($user);
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }


    public function dummyUser(){
        $password = 'password123';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);
        $token = Auth::attempt(['email' => $user->email, 'password' => $password]);
        return response()->json(['user' => $user,'token' => $token], 200);
    }
}
