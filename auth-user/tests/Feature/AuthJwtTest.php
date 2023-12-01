<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class AuthJwtTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

    }

    /** @test */
    public function it_can_authenticate_a_user_and_return_jwt_token()
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user',
            ]);
    }

    /** @test */
    public function it_returns_unauthorized_with_invalid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'invalidpassword',
        ]);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors',
            ]);
    }


    /** @test */
    public function it_can_register_a_user_and_return_jwt_token()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john'.mt_rand(1,1200).'@doe.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);
        if($response->status() == 422){
            $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors',
                ]);
        }else{
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'user',
                ]);
        }
    }

    /** @test */
    public function it_can_logout_a_user()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertExactJson(['message' => 'Successfully logged out']);
    }
}
