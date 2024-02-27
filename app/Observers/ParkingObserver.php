<?php

namespace App\Observers;

use App\Models\Parking;

class ParkingObserver
{
    /**
     * Handle the Parking "created" event.
     */
    public function creating(Parking $parking): void
    {
        if (auth()->check()) {
            $parking->user_id = auth()->id();
        }

        $parking->start_time = now();
    }
}
