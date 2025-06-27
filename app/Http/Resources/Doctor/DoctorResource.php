<?php

namespace App\Http\Resources\Doctor;

use App\Http\Resources\Patient\PatientResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'doctor_name' => $this->user?->name,
            'phone_number' => $this->phone_number,
            'Specialization' => $this->Specialization,
            'patients' => PatientResource::collection($this->whenLoaded('patients')),


        ];
    }
}
