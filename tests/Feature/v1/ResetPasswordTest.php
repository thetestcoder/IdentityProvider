<?php

namespace Tests\Feature\v1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{


    /** @test */
    public function it_handles_error_resetting_password()
    {
        $data = [
            'email' => 'nonexistent@example.com', // Assuming this email doesn't exist
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => 'invalidtoken', // Assuming this token is invalid
        ];

        $response = $this->postJson('/api/v1/password/reset', $data);

        $response->assertStatus(500);
    }
    /**
     * A basic feature test example.
     */
    public function it_resets_password_successfully()
    {
        $data = [
            'email' => 'nonexistent@example.com', // Assuming this email doesn't exist
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => 'invalidtoken', // Assuming this token is invalid
        ];

        $response = $this->postJson('/api/v1/password/reset', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Password reset successful'
            ]);
    }
}
