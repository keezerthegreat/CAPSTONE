<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Always compute age from birthdate so it stays accurate over time.
     * Falls back to the stored value if birthdate is unavailable.
     */
    public function getAgeAttribute(mixed $value): ?int
    {
        $birthdate = $this->attributes['birthdate'] ?? null;
        if ($birthdate) {
            return Carbon::parse($birthdate)->age;
        }

        return $value ? (int) $value : null;
    }

    /**
     * Senior status is derived from age (60+) so it never goes stale.
     * Falls back to the stored flag only when age is unknown.
     */
    public function getIsSeniorAttribute(mixed $value): bool
    {
        $age = $this->age;
        if ($age !== null) {
            return $age >= 60;
        }

        return (bool) $value;
    }

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
