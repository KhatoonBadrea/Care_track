<?php
namespace App\Http\Resources\TreatmentPlan;

use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentPlanResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'patient_id'   => $this->patient_id,
            'diagnosis'    => $this->diagnosis,
            'medications'  => $this->medications,
            'instructions' => $this->instructions,
            'start_date'   => $this->start_date,
            'end_date'     => $this->end_date,
            'created_at'   => $this->created_at->toDateTimeString(),
        ];
    }
}
