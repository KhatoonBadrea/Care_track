<?php

namespace App\Services\TreatmentPlan;

use Exception;
use App\Models\User;
use App\Models\TreatmentPlan;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Services\Result\ServiceResult;
use App\Http\Requests\TreatmentPlan\StorTreatmentPlanRequest;

class TreatmentPlanService
{

    public function index(User $user): ServiceResult
    {
        try {
            $treatmentPlans = TreatmentPlan::query()
                ->with(['patient.user', 'doctor.user'])
                ->FilterByRole($user)
                ->latest()
                ->paginate(10);

            return new ServiceResult(true, $treatmentPlans);
        } catch (\Exception $e) {
            Log::error('Failed to fetch treatment plans: ' . $e->getMessage());
            return new ServiceResult(false, null, 'Failed to fetch treatment plans.');
        }
    }



    public function show(User $user, TreatmentPlan $treatmentPlan): ServiceResult
    {
        try {
            $isAllowed = TreatmentPlan::query()
                ->FilterByRole($user)
                ->where('id', $treatmentPlan->id)
                ->exists();

            if (!$isAllowed) {
                return new ServiceResult(false, null, 'Unauthorized to view this treatment plan.');
            }

            return new ServiceResult(true, $treatmentPlan);
        } catch (\Exception $e) {
            Log::error('Failed to fetch treatment plan: ' . $e->getMessage());
            return new ServiceResult(false, null, 'Error fetching treatment plan.');
        }
    }




    public function store(StorTreatmentPlanRequest $request): ServiceResult
    {
        $user = JWTAuth::parseToken()->authenticate();
        $doctor = $user->doctor;

        if (!$doctor) {
            return new ServiceResult(false, null, 'Doctor not found for this user.');
        }

        $patientId = $request->input('patient_id');

        // ✅ تأكد أن المريض مرتبط بهالدكتور
        if (!$doctor->patients->pluck('id')->contains($patientId)) {
            return new ServiceResult(false, null, 'هذا المريض غير مرتبط بك.');
        }

        $plan = TreatmentPlan::create([
            'doctor_id'    => $doctor->id,
            'patient_id'   => $patientId,
            'diagnosis'    => $request->input('diagnosis'),
            'medications'  => $request->input('medications'),
            'instructions' => $request->input('instructions'),
            'start_date'   => $request->input('start_date'),
            'end_date'     => $request->input('end_date'),
        ]);

        return new ServiceResult(true, $plan, 'تم إنشاء خطة العلاج بنجاح.');
    }



    public function update(TreatmentPlan $treatmentPlan, array $data): ServiceResult
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // $doctor = $user->doctor;

            // // التحقق من الدور والعلاقة
            // if ($user->role !== 'doctor' || !$user->doctor) {
            //     return new ServiceResult(false, null, 'Unauthorized: Not a doctor.');
            // }

            $doctorId = $user->doctor->id;

            // هل الدكتور مرتبط بالمريض؟
            $isAssigned = $treatmentPlan->patient
                ->doctors()
                ->where('doctor_id', $doctorId)
                ->exists();

            if (!$isAssigned) {
                return new ServiceResult(false, null, 'Unauthorized: You are not assigned to this patient.');
            }

            // تنفيذ التعديل
            $treatmentPlan->update($data);

            return new ServiceResult(true, $treatmentPlan);
        } catch (\Exception $e) {
            Log::error('Failed to update treatment plan: ' . $e->getMessage());
            return new ServiceResult(false, null, 'Error: Failed to update treatment plan.');
        }
    }


    public function delete(User $user, TreatmentPlan $treatmentPlan): ServiceResult
    {
        try {
            $isAllowed = TreatmentPlan::query()
                ->FilterByRole($user) // نحدد نوع الوصول
                ->where('id', $treatmentPlan->id)
                ->exists();

            // إذا أدمن، مسموح مباشرة
            if ($user->role === 'admin') {
                $isAllowed = true;
            }

            if (!$isAllowed) {
                return new ServiceResult(false, null, 'Unauthorized to delete this treatment plan.');
            }

            $treatmentPlan->delete();

            return new ServiceResult(true, null, 'Treatment plan deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete treatment plan: ' . $e->getMessage());
            return new ServiceResult(false, null, 'Error deleting treatment plan.');
        }
    }
}
