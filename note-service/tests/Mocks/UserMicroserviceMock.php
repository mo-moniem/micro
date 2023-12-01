<?php

namespace Tests\Mocks;

use Illuminate\Support\Facades\Http;

class UserMicroserviceMock
{
    public function getUser()
    {
        $response = Http::get(config('microservices.user.url').'/auth/dummy-user');
//        dd(1,$response->json());
        // Return the mock user data based on the provided user ID
        return $response->json();
    }
}
