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

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'blood_clique_id',
        'gender',
        'birth_date',
        'notes',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
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

    public function relatives(): HasMany
    {
        return $this->hasMany(Relative::class);
    }
}
