<?php

namespace App\Services\Relative;

use Exception;
use App\Models\Relative;
use Illuminate\Support\Facades\Log;
use App\Services\Result\ServiceResult;

class RelativeService
{
    public function create(array $data)
    {
        try {

            $relative = Relative::create($data);
            // if (!$relative) {
            //     return new ServiceResult(false, null, "User not found");
            // }
            return new ServiceResult(true, $relative);
        } catch (Exception $e) {
            Log::error('Failed to create relative: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to create relative");
        }
    }

    public function update(Relative $relative, array $data)
    {
        try {

            $relative->update($data);
            return new ServiceResult(true, $relative);
        } catch (Exception $e) {
            Log::error('Failed to update relative: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to update relative");
        }
    }

    public function delete(Relative $relative)
    {
        try {
            if (!$relative) {
                return new ServiceResult(false, null, "relative not found");
            }

            $relative->delete();
            return new ServiceResult(true, $relative);
        } catch (Exception $e) {
            Log::error('Failed to delete relative: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to delete relative");
        }
    }


    // public function getByPatientId(int $patientId)
    // {
    //     try {

    //         return Relative::with('relatives.doctors.user')
    //             ->where('patient_id', $patientId)
    //             ->get();
    //     } catch (Exception $e) {
    //         Log::error('Failed to create relative: ' . $e->getMessage());
    //     }
    // }

    public function getPatientDetailsForRelative(int $relativeId)
    {
        try {
            $relative = Relative::with('patients.doctors.user')->findOrFail($relativeId);
            // return [
            //     'success' => true,
            //     'patient' => $relative->patient
            // ];
            if (!$relative) {
                return new ServiceResult(false, null, "User not found");
            }
            return new ServiceResult(true, $relative->patient);
        } catch (\Exception $e) {
            Log::error('Failed to get details: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to get details");
        }
    }
}
