<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodClique extends Model
{
    protected $fillable = [
        'blood_clique',
    ];

    protected  $table = 'blood_cliques';


    /**
     * Get all patients with this blood type.
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }
}