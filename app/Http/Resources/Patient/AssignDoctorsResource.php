<?php

namespace App\Http\Resources\Patient;

use Illuminate\Http\Request;
use App\Http\Resources\Doctor\DoctorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignDoctorsResource extends JsonResource
{
    /** 
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'patient' => new PatientResource($this['patient']),
            'doctors' => DoctorResource::collection($this['doctors']),
        ];
    }
}
