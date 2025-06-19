<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relative extends Model
{
    protected $fillable = [
        'patient_id',
        'name',
        'relation',
        'phone',
        'email',
    ];
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
