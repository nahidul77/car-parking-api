<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParkingResource;
use App\Models\Parking;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    public function start(Request $request)
    {
        $parkingData = $request->validate([
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'zone_id' => ['required', 'integer', 'exists:zones,id']
        ]);

        $parking = Parking::create($parkingData);
        $parking->load('vehicle', 'zone');

        return ParkingResource::make($parking);
    }

    public function show(Parking $parking)
    {
        return ParkingResource::make($parking);
    }

    public function stop(Parking $parking)
    {
        $parking->update([
            'end_time' => now(),
        ]);

        return ParkingResource::make($parking);
    }
}
