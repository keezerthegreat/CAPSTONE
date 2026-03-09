<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentPendingEdit extends Model
{
    protected $fillable = [
        'resident_id',
        'proposed_data',
        'submitted_by_id',
        'submitted_by_name',
    ];

    protected $casts = [
        'proposed_data' => 'array',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
