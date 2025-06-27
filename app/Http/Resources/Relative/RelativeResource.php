<?php

namespace App\Http\Resources\Relative;

use Illuminate\Http\Request;
use App\Http\Resources\Patient\PatientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RelativeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'relation' => $this->relation,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
}
