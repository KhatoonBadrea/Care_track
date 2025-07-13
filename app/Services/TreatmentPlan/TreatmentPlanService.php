<?php

namespace App\Services\TreatmentPlan;

use Exception;
use App\Models\TreatmentPlan;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Services\Result\ServiceResult;
use App\Http\Requests\TreatmentPlan\StorTreatmentPlanRequest;

class TreatmentPlanService
{
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


       public function update(TreatmentPlan $treatmentPlan, array $data)
    {
        try {

            $treatmentPlan->update($data);

            return new ServiceResult(true, $treatmentPlan);
        } catch (Exception $e) {
            Log::error('Failed to update vitalSign: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to update treatment Plan");
        }
    }
}
