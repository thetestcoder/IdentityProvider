<?php

namespace Tests\Feature\v1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\__FIXTURE__\Passport;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Passport::generatePersonAccessToken();
    }

    public function test_empty_register(): void
    {
        $response = $this->post('/api/v1/register');
        $response->assertStatus(422);
    }



    public function test_successful_registration()
    {
        $data=[
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123', // Add this line
            'site_url' => 'http://example.com',
        ];
        $response = $this->json('post', '/api/v1/register', $data);
        $response->assertStatus(200)
            ->assertJson([
                'message' => "Registered Successfully"
            ])->assertJsonStructure([
            'message',
                'data'=>[
                    'token'
                ]
            ]);
    }
    public function test_name_is_null()
    {
        $data = [
            'name' => null,  // Set name as null
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'site_url' => 'http://example.com',
        ];

        $response = $this->json('post', '/api/v1/register', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_email_is_null()
    {
        $data = [
            'name' => 'John Doe',
            'email' => null,  // Set email as null
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'site_url' => 'http://example.com',
        ];

        $response = $this->json('post', '/api/v1/register', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_password_is_null()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => null,  // Set password as null
            'password_confirmation' => 'password123',
            'site_url' => 'http://example.com',
        ];

        $response = $this->json('post', '/api/v1/register', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

}