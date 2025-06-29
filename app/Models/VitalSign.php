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




    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereBetween('measured_at', [$from, $to]);
    }



    public function scopeFilterByPatientName($query, $name)
    {
        return $query->whereHas('patient.user', function ($q) use ($name) {
            $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%']);
        });
    }
}
