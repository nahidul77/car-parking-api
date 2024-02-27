<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(201);
    }

    public function testUserCannotLoginWithIncorrectCredentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(422);
    }

    public function testUserCanRegisterWithCorrectCredentials()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'Nahidul Islam',
            'email' => 'nahid@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'access_token'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Nahidul Islam',
            'email' => 'nahid@example.com'
        ]);
    }

    public function testUserCannotRegisterWithIncorrectCredentials()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'Nahidul Islam',
            'email' => 'nahid@example.com',
            'password' => 'password',
            'password_confirmation' => 'wrong_password'
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'name' => 'Nahidul Islam',
            'email' => 'nahid@example.com'
        ]);
    }
}
