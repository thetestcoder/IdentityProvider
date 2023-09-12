<?php

namespace Tests\Feature\v1;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\Feature\__FIXTURE__\Passport;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        Passport::generatePersonAccessToken();
    }
    /** @test */
    public function test_changes_password()
    {
        // Create a user
        $user = User::create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        $token = $user->createToken('TestToken')->accessToken;
        $data = [
            'old_password' => '12345678',
            'new_password' => '87654321',
            'new_password_confirmation' => '87654321',
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->json('POST', '/api/v1/password/change', $data);
        $response->assertStatus(200);
    }


    /** @test */
    public function test_incorrect_old_password()
    {
        // Create a user
        $user = \App\Models\User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('old_password')
        ]);
        // Login the user
        $this->actingAs($user);
        $token = $user->createToken('TestToken')->accessToken;
        // Define the request data with incorrect old password
        $data = [
            'old_password' => 'wrong_password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ];
        // Send a POST request to the changePassword endpoint
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',

        ])->json('POST', '/api/v1/password/change', $data);
        $response->assertStatus(401);
    }

}
