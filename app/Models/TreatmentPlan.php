<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentPlan extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'diagnosis',
        'medications',
        'instructions',
        'start_date',
        'end_date',
    ];




      public function scopeFilterByRole($query, $user)
    {
        return $query->when($user->role === 'patient', function ($q) use ($user) {
            $patientId = optional($user->patient)->id;
            return $q->where('patient_id', $patientId);
        })
            ->when($user->role === 'relative', function ($q) use ($user) {
                return $q->whereHas('patient.relative', function ($qr) use ($user) {
                    $qr->where('user_id', $user->id);
                });
            })
            ->when($user->role === 'doctor', function ($q) use ($user) {
                return $q->whereHas('patient.doctors', function ($qd) use ($user) {
                    $qd->where('user_id', $user->id);
                });
            });
    }

    

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
