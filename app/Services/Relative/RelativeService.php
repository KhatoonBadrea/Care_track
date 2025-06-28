<?php

namespace App\Services\Relative;

use Exception;
use App\Models\Patient;
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




   public function getPatientDetailsForRelative(int $relativeId)
{
    try {
        $patients = Patient::with('doctors.user')
            ->where('relative_id', $relativeId)
            ->get();

        return new ServiceResult(true, $patients);
    } catch (\Exception $e) {
        Log::error('Failed to get patient details for relative: ' . $e->getMessage());
        return new ServiceResult(false, null, "Error: Failed to get patient details");
    }
}

}
