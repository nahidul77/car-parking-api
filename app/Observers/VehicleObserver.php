<?php

namespace App\Observers;

use App\Models\Vehicle;

class VehicleObserver
{
    /**
     * Handle the Vehicle "creating" event.
     */
    public function creating(Vehicle $vehicle)
    {
        if (auth()->check()) {
            $vehicle->user_id = auth()->id();
        }
    }
}
