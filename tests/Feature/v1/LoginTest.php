<?php

namespace Tests\Feature\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\__FIXTURE__\Passport;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        Passport::generatePersonAccessToken();
    }
    public function test_empty_login()
    {
        $response = $this->post('/api/v1/login', [
            'email' => '',
            'password' => '',
        ]);
//        dd($response);
        return $response->assertStatus(422);
    }
    public function test_invalid_login()
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        return $response->assertStatus(400);
    }
    public function test_successful_login()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        return $response->assertStatus(200);
    }





}