<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        // Personal Info
        'last_name',
        'first_name',
        'middle_name',
        'gender',
        'birthdate',
        'age',
        'civil_status',
        'nationality',
        'religion',

        // Contact
        'contact_number',
        'email',
        'philsys_number',

        // Address
        'province',
        'city',
        'barangay',
        'address',

        // Socio-economic
        'occupation',
        'employer',
        'monthly_income',
        'education_level',

        // Special classification
        'is_senior',
        'is_pwd',
        'is_voter',

        // Location
        'latitude',
        'longitude',

        // Deceased
        'is_deceased',
        'date_of_death',

        // Family link
        'family_id',
        'family_role',

        // Household link
        'household_id',

        // Verification
        'status',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function household()
    {
        return $this->belongsTo(Household::class);
    }
}
