<?php

namespace App\Services\VitalSign;

use Exception;
use App\Models\User;
use App\Models\VitalSign;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Services\Result\ServiceResult;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\VitalSign\VitalSignResource;

class VitalSignService
{

    public function create(array $data)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $patient = $user->patient;

        if (!$patient) {
            return new ServiceResult(false, null, 'Patient not found for this user.');
        }

        try {
            $vitalSign = VitalSign::create([
                'patient_id' => $patient->id,
                'temperature' => $data['temperature'],
                'heart_rate' => $data['heart_rate'],
                'blood_pressure_systolic' => $data['blood_pressure_systolic'],
                'blood_pressure_diastolic' => $data['blood_pressure_diastolic'],
                'respiratory_rate' => $data['respiratory_rate'],
                'measured_at' => Now(),

            ]);
            // dd($vitalSign);
            return new ServiceResult(true, $vitalSign);
        } catch (Exception $e) {
            Log::error('Failed to create vitalSign: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to create vitalSign");
        }
    }


    public function update(VitalSign $vitalSign, array $data)
    {
        try {

            $vitalSign->update($data);

            return new ServiceResult(true, $vitalSign);
        } catch (Exception $e) {
            Log::error('Failed to update vitalSign: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to update vitalSign");
        }
    }


    public function delete(VitalSign $vitalSign)
    {
        try {

            if (!$vitalSign) {
                return new ServiceResult(false, null, "vitalSign not found");
            }
            $vitalSign->delete();
            return new ServiceResult(true, $vitalSign);
        } catch (Exception $e) {
            Log::error('Failed to delete vitalSign: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to delete vitalSign");
        }
    }


    public function index(array $filters, User $user): ServiceResult
{
    try {
        $query = VitalSign::query()
            ->filterByRole($user)
            ->when($filters['from'] ?? null && $filters['to'] ?? null, fn($q) =>
                $q->betweenDates($filters['from'], $filters['to']))
            ->when($filters['patient_name'] ?? null, fn($q) =>
                $q->filterByPatientName($filters['patient_name']));

        $groups = $query->with('patient.user')->get()->groupBy('patient_id');

        $averages = $groups->map(function ($group) {
            $first = $group->first();
            return [
                'patient_name'     => optional($first->patient->user)->name,
                'avg_temperature'  => round($group->avg('temperature'), 2),
                'avg_heart_rate'   => round($group->avg('heart_rate'), 2),
                'avg_systolic'     => round($group->avg('blood_pressure_systolic'), 2),
                'avg_diastolic'    => round($group->avg('blood_pressure_diastolic'), 2),
            ];
        })->values();

        // Manual pagination
        $perPage = 10;
        $page = request('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $averages->forPage($page, $perPage)->values(),
            $averages->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return new ServiceResult(true, $paginated);
    } catch (\Exception $e) {
        Log::error('Failed to load vital signs: ' . $e->getMessage());
        return new ServiceResult(false, null, 'Failed to fetch data');
    }
}

}
