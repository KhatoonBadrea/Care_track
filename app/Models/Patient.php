<?php

namespace App\Models;

use App\Models\User;
use App\Models\Alert;
use App\Models\Relative;
use App\Models\VitalSign;
use App\Models\BloodClique;
use App\Models\TreatmentPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'relative_id',
        'blood_clique_id',
        'gender',
        'birth_date',
        'notes',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class, 'doctor_patient', 'patient_id', 'doctor_id')->with('user');
    }


    public function bloodClique(): BelongsTo
    {
        return $this->belongsTo(BloodClique::class);
    }

    public function vitalSigns(): HasMany
    {
        return $this->hasMany(VitalSign::class);
    }

    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    public function relative()
    {
        return $this->belongsTo(Relative::class);
    }
}
