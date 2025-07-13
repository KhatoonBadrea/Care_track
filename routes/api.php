<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Doctor\DoctorController;
use App\Http\Controllers\Api\Patient\PatientController;
use App\Http\Controllers\Api\Relative\RelativeController;
use App\Http\Controllers\Api\VitalSign\VitalSignController;
use App\Http\Controllers\Api\Notification\NotificationController;
use App\Http\Controllers\Api\TreatmentPlan\TreatmentPlanController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});


//========================== admin route

Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {

    Route::apiResource('users', UserController::class);

    Route::apiResource('doctors', DoctorController::class)->except(['show']);

    Route::apiResource('patients', PatientController::class);
    Route::post('patients/{patient}/assign-doctors', [PatientController::class, 'assignDoctors']);


    Route::apiResource('relatives', RelativeController::class);
});

// =========================Doctor Route
Route::get('doctors/{doctor}', [DoctorController::class, 'show'])->middleware(['auth:api', 'role:doctor,admin']);

//=========================Relative Route
Route::get('/relative/{relativeId}/patients', [RelativeController::class, 'showPatientDetails'])->middleware(['auth:api', 'role:relative,admin']);


//========================Patient Route

Route::middleware(['auth:api'])->group(function () {

    Route::apiResource('vitalSign', VitalSignController::class);
});


Route::middleware('auth:api')->get('/notifications', [NotificationController::class, 'index']);

Route::middleware('auth:api')->post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

Route::middleware('auth:api')->delete('/notifications/{id}', [NotificationController::class, 'destroy']);

//==================Treatment Plan
Route::apiResource('treatment-plan', TreatmentPlanController::class);
Route::get('/doctor/patients', [TreatmentPlanController::class, 'LinkedPatient']);
