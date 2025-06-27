<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relative extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'relation',
        'phone',
        'email',
    ];
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
