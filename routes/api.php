<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Doctor\DoctorController;
use App\Http\Controllers\Api\Patient\PatientController;
use App\Http\Controllers\Api\Relative\RelativeController;
use App\Http\Controllers\Api\User\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::get( 'doctors/{doctor}',[DoctorController::class, 'show'])->middleware(['auth:api', 'role:doctor|admin']);

Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {

    Route::apiResource('users', UserController::class);

    Route::apiResource('doctors', DoctorController::class)->except(['show']);

    Route::apiResource('patients', PatientController::class);
    Route::post('patients/{patient}/assign-doctors', [PatientController::class, 'assignDoctors']);


    Route::apiResource('relatives', RelativeController::class);
    Route::get('patients/{patient}/relatives', [RelativeController::class, 'getByPatient']);
});

Route::middleware(['auth:api', 'role:relative'])->group(function () {

    Route::get('relatives/{relative}/patient', [RelativeController::class, 'showPatientDetails']);
});
