<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetTheirOwnVehicles()
    {
        $alice = User::factory()->create();

        $vehicleForAlice = Vehicle::factory()->create([
            'user_id' => $alice->id,
        ]);

        $bob = User::factory()->create();

        $vehicleForBob = Vehicle::factory()->create([
            'user_id' => $bob->id,
        ]);

        $response = $this->actingAs($alice)->getJson(route('vehicles.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.plate_number', $vehicleForAlice->plate_number)
            ->assertJsonMissing($vehicleForBob->toArray());
    }

    public function testUserCanCreateVehicle()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('vehicles.store'), [
            'plate_number' => 'ABC123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'plate_number']
            ])
            ->assertJsonPath('data.plate_number', 'ABC123');

        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'ABC123',
        ]);
    }

    public function testUserCanUpdateTheirVehicle()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->putJson(route('vehicles.update', $vehicle->id), [
            'plate_number' => 'ABC123'
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure(['plate_number'])
            ->assertJsonPath('plate_number', 'ABC123');

        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'ABC123',
        ]);
    }

    public function testUserCanDeleteTheirVehicle()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson(route('vehicles.destroy', $vehicle->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->id,
            'deleted_at' => NULL
        ])->assertDatabaseCount('vehicles', 0);
    }
}
