<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
   protected $fillable=[
    'patient_id',
    'type',
    'message',
    'is_read',
    'alerted_at',
   ];


    public function patient(): BelongsTo
{
    return $this->belongsTo(Patient::class);
}

}