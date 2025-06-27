<?php

namespace App\Http\Resources\Patient;

use Illuminate\Http\Request;
use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Resources\Relative\RelativeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_name' => $this->user?->name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'notes' => $this->notes,
            'blood_clique' => $this->bloodClique?->blood_clique,
            'doctors' => DoctorResource::collection($this->whenLoaded('doctors')),
            'relative' => new RelativeResource($this->whenLoaded('relative')),

            // 'doctors' => $this->doctors->pluck('name'),
            // 'created_at' => $this->created_at,
        ];
    }
}
