<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
        'family_name',
        'head_last_name',
        'head_first_name',
        'head_middle_name',
        'household_id',
        'member_count',
        'notes',
    ];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }
}
