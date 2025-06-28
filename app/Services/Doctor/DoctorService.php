<?php

namespace App\Services\Doctor;

use Exception;
use App\Models\Doctor;
use Illuminate\Support\Facades\Log;
use App\Services\Result\ServiceResult;

class DoctorService
{

    public function create(array $data)
    {
        try {
            $doctor = Doctor::create([
                'user_id' => $data['user_id'],
                'Specialization' => $data['Specialization'],
                'phone_number' => $data['phone_number'],

            ]);
            return new ServiceResult(true, $doctor);
        } catch (Exception $e) {
            Log::error('Failed to create doctor: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to create doctor");
        }
    }


    public function update(Doctor $doctor, array $data)
    {
        try {

            $doctor->update($data);

            return new ServiceResult(true, $doctor);
        } catch (Exception $e) {
            Log::error('Failed to update doctor: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to update doctor");
        }
    }


    public function delete(Doctor $doctor)
    {
        try {

            if (!$doctor) {
                return new ServiceResult(false, null, "doctor not found");
            }
            $doctor->delete();
            return new ServiceResult(true, $doctor);
        } catch (Exception $e) {
            Log::error('Failed to delete doctor: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to delete doctor");
        }
    }
}
