<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetTheirProfile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('profile.show'));

        $response->assertStatus(200)
            ->assertJsonStructure(['name', 'email'])
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => $user->name]);
    }

    public function testUserCanUpdateNameAndEmail()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson(route('profile.update'), [
            'name' => 'Nahidul Islam',
            'email' => 'nahid@app.com',
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure(['name', 'email'])
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => 'Nahidul Islam']);

        $this->assertDatabaseHas('users', [
            'name' => 'Nahidul Islam',
            'email' => 'nahid@app.com',
        ]);
    }

    public function testUserCanChangePassword()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson(route('password.update'), [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertStatus(202);
    }
}
