<?php

namespace Tests\Feature\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForgetPasswordTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function test_sends_password_reset_email()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        $data = [
            'email' => 'test@example.com'
        ];

        $response = $this->postJson('/api/v1/password/email', $data);

        $response->assertStatus(200);
    }

    public function test_handles_error_sending_password_reset_email()
    {
        // Simulate an error in sending the reset link email
        // For example, if the email doesn't exist in the database

        $data = [
            'email' => 'nonexistent@example.com'
        ];

        $response = $this->postJson('/api/v1/password/email', $data);

        $response->assertStatus(500);
    }
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/v1/password/email');

        $response->assertStatus(405);
    }
}
