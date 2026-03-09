<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    protected $fillable = [
        'household_number',
        'head_resident_id',
        'head_last_name',
        'head_first_name',
        'head_middle_name',
        'sitio',
        'street',
        'barangay',
        'city',
        'province',
        'member_count',
        'residency_type',
        'latitude',
        'longitude',
        'notes',
    ];

    public function headResident()
    {
        return $this->belongsTo(\App\Models\Resident::class, 'head_resident_id');
    }

    public function members()
    {
        return $this->hasMany(\App\Models\Resident::class, 'household_id');
    }
}