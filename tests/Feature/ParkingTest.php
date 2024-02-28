<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParkingTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanStartParking()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        $zone = Zone::first();

        $response = $this->actingAs($user)->postJson(route('parking.start'), [
            'vehicle_id' => $vehicle->id,
            'zone_id' => $zone->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data'])
            ->assertJson([
                'data' => [
                    'start_time' => now()->toDateTimeString(),
                    'stop_time' => null,
                    'total_price' => 0,
                ]
            ]);

        $this->assertDatabaseCount('parkings', '1');
    }
}
