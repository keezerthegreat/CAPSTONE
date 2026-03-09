<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
        'family_name',
        'head_resident_id',
        'head_last_name',
        'head_first_name',
        'head_middle_name',
        'head_role',
        'household_id',
        'member_count',
        'notes',
    ];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function headResident()
    {
        return $this->belongsTo(\App\Models\Resident::class, 'head_resident_id');
    }

    public function members()
    {
        return $this->hasMany(\App\Models\Resident::class, 'family_id');
    }
}
