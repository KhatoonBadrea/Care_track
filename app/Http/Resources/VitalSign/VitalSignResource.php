<?php

namespace App\Http\Resources\VitalSign;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VitalSignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'patient_name' => $this->patient?->user?->name,
            'temperature' => $this->temperature,
            'heart_rate' => $this->heart_rate,
            'blood_pressure_systolic' => $this->blood_pressure_systolic,
            'blood_pressure_diastolic' => $this->blood_pressure_diastolic,
            'respiratory_rate' => $this->respiratory_rate,
            'measured_at' => $this->measured_at,
        ];
    }
}
