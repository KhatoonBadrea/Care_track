<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentPlan extends Model
{
    protected $fillable = [
        'patient_id',
        'diagnosis',
        'medications',
        'instructions',
        'start_date',
        'end_date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
