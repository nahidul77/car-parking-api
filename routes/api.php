<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\PasswordUpdateController;
use App\Http\Controllers\Api\V1\ParkingController;
use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\ZoneController;
use App\Models\Parking;
use App\Models\Vehicle;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', LogoutController::class);

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('password', PasswordUpdateController::class)->name('password.update');

    Route::apiResource('vehicles', VehicleController::class);

    Route::post('parking/start', [ParkingController::class, 'start'])->name('parking.start');
    Route::get('parking/{parking}', [ParkingController::class, 'show'])->name('parking.show');
    Route::put('parking/{parking}', [ParkingController::class, 'stop'])->name('parking.stop');
});

Route::post('auth/register', RegisterController::class)->name('register');
Route::post('auth/login', LoginController::class)->name('login');

Route::get('zones', [ZoneController::class, 'index'])->name('all.zones');
