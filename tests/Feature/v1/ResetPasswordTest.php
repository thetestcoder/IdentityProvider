<?php

namespace Tests\Feature\v1;

use Tests\TestCase;
use App\Models\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Requests\V1\ResetPasswordRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ResetPasswordTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('migrate',['-vvv' => true]);
        \Artisan::call('passport:install',['-vvv' => true]);
        \Artisan::call('db:seed',['-vvv' => true]);
    }

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
    
    public function test_resets_password_successfully()
    {
        // Create a user manually
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password'),
        ]);

        // Generate a reset token
        $token = Password::createToken($user);

        // Mock a valid request
        $request = new ResetPasswordRequest([
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => $token,
        ]);

        // Call the reset method
        $response = $this->postJson('/api/v1/password/reset', $request->all());
        $response->assertStatus(200);
    
    }

  
}
