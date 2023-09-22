<?php

namespace Tests\Feature\v1;

use App\Models\User;
use Tests\TestCase;

class AuthenticatedUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('migrate',['-vvv' => true]);
        \Artisan::call('passport:install',['-vvv' => true]);
        \Artisan::call('db:seed',['-vvv' => true]);
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_return_the_auth_user_if_token_is_valid(): void
    {
       User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $data = json_decode($response->getContent(), true);

        $token = $data['data']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->json('POST', '/api/v1/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data'=>[
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'signup_url'
                ]
            ]);

    }
}
