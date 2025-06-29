<?php

namespace App\Http\Resources\VitalSign;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VitalSignAverageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'patient_name' => $this['patient_name'],
            'avg_temperature' => $this['avg_temperature'],
            'avg_heart_rate' => $this['avg_heart_rate'],
            'avg_systolic' => $this['avg_systolic'],
            'avg_diastolic' => $this['avg_diastolic'],
        ];
    }
}
