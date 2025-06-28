<?php

namespace App\Services\Patient;

use Exception;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;
use App\Services\Result\ServiceResult;



class PatientService
{
    public function create(array $data)
    {
        try {

            $patient = Patient::create([
                'user_id' => $data['user_id'],
                'relative_id' => $data['relative_id'],
                'gender' => $data['gender'],
                'birth_date' => $data['birth_date'],
                'notes' => $data['notes'] ?? null,
                'blood_clique_id' => $data['blood_clique_id'],
            ]);

            return new ServiceResult(true, $patient);
        } catch (Exception $e) {
            Log::error('Failed to create patient: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to create patient");
        }
    }





    public function assignDoctors(Patient $patient, array $doctorIds)
    {
        try {
            $validDoctorIds = Doctor::whereIn('id', $doctorIds)->pluck('id')->toArray();

            $patient->doctors()->syncWithoutDetaching($validDoctorIds);

            $doctors = Doctor::with('user')->whereIn('id', $validDoctorIds)->get();

            return new ServiceResult(true, [
                'patient' => $patient->load('user'),
                'doctors' => $doctors,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to assign doctors: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error: Failed to assign doctors");
        }
    }




    public function update(Patient $patient, array $data)
    {
        try {

            $patient->update($data);

            if (isset($data['doctor_ids'])) {
                $patient->doctors()->sync($data['doctor_ids']);
            }

            return new ServiceResult(true, $patient);
        } catch (Exception $e) {
            Log::error('Failed to update patient: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to update patient");
        }
    }



    public function delete(Patient $patient)
    {
        try {

            if (!$patient) {
                return new ServiceResult(false, null, "patient not found");
            }
            $patient->doctors()->detach();
            $patient->delete();
            return new ServiceResult(true, $patient);
        } catch (Exception $e) {
            Log::error('Failed to delete patient: ' . $e->getMessage());
            return new ServiceResult(false, null, "Error:Faild to delete patient");
        }
    }
}
