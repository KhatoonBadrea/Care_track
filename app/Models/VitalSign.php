<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VitalSign extends Model
{
      protected $fillable = [
        'patient_id',
        'temperature',
        'heart_rate',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'respiratory_rate',
        'measured_at',
    ];


    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
