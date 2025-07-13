<?php

namespace App\Services\VitalSign;

use Exception;
use App\Models\User;
use App\Models\VitalSign;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Services\Result\ServiceResult;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Notification\NotificationService;
use App\Http\Resources\VitalSign\VitalSignResource;

class VitalSignService
{

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function create(array $data): ServiceResult
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
                'measured_at' => $data['measured_at'] ?? now(),
            ]);

            $shouldAlert = $this->checkForAlerts($data);

            if ($shouldAlert) {
                $this->notificationService->sendAlertToDoctors($patient->id, $vitalSign);
            }

            return new ServiceResult(true, $vitalSign);
        } catch (Exception $e) {
            Log::error('Failed to create vitalSign: ' . $e->getMessage());
            return new ServiceResult(false, null, "Failed to create vitalSign");
        }
    }


    public function checkForAlerts(array $vitalData): bool
    {
        if ($vitalData['temperature'] > 39) return true;
        if ($vitalData['blood_pressure_systolic'] > 140 || $vitalData['blood_pressure_diastolic'] > 90) return true;
        if ($vitalData['heart_rate'] > 120 || $vitalData['heart_rate'] < 50) return true;
        if ($vitalData['respiratory_rate'] > 30) return true;

        return false;
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
            // بناء الاستعلام مع الفلاتر
            $query = VitalSign::query()
                ->filterByRole($user)
                ->when($filters['from'] ?? null && $filters['to'] ?? null, fn($q) =>
                $q->betweenDates($filters['from'], $filters['to']))
                ->when($filters['patient_name'] ?? null, fn($q) =>
                $q->filterByPatientName($filters['patient_name']));

            // تسجيل الاستعلام للفلترة (اختياري لتصحيح الأخطاء)
            $averagesQuery = $query
                ->selectRaw('
        patient_id,
        AVG(temperature) as avg_temperature,
        AVG(heart_rate) as avg_heart_rate,
        AVG(blood_pressure_systolic) as avg_systolic,
        AVG(blood_pressure_diastolic) as avg_diastolic
    ')
                ->groupBy('patient_id')
                ->with('patient.user');

            // سجل الاستعلام الحقيقي المعدل:
            Log::info('Filtered SQL:', [
                'sql' => $averagesQuery->toSql(),
                'bindings' => $averagesQuery->getBindings()
            ]);

            $averages = $averagesQuery->get()->map(function ($item) {
                return [
                    'patient_name'    => optional($item->patient->user)->name,
                    'avg_temperature' => round($item->avg_temperature, 2),
                    'avg_heart_rate'  => round($item->avg_heart_rate, 2),
                    'avg_systolic'    => round($item->avg_systolic, 2),
                    'avg_diastolic'   => round($item->avg_diastolic, 2),
                ];
            });


            // التصفح اليدوي
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
