<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/car-services', function () {
        $services = App\Models\CarService::with('customer')
            ->select('id', 'car_make', 'car_model', 'service_date')
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'car_make' => $service->car_make,
                    'car_model' => $service->car_model,
                    'customer_name' => $service->customer->name,
                    'service_date' => $service->service_date
                ];
            });
        return response()->json($services);
    });
});