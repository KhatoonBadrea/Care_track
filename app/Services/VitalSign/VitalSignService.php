<?php

namespace App\Services\VitalSign;

use Exception;
use App\Models\VitalSign;
use Illuminate\Support\Facades\Log;
use App\Services\Result\ServiceResult;

class VitalSignService
{

    public function create(array $data)
    {
        try {
            $vitalSign = VitalSign::create([
                'patient_id' => $data['patient_id'],
                'temperature' => $data['temperature'],
                'heart_rate' => $data['heart_rate'],
                'blood_pressure_systolic' => $data['blood_pressure_systolic'],
                'blood_pressure_diastolic' => $data['blood_pressure_diastolic'],
                'respiratory_rate' => $data['respiratory_rate'],
                'measured_at' => $data['measured_at'],

            ]);
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
}
