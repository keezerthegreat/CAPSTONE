<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'birthdate',
        'gender',
        'civil_status',
        'address',
        'contact_number',
        'email',
        'position',
        'date_hired',
        'employment_status',
    ];

    protected $casts = [
        'birthdate'  => 'date',
        'date_hired' => 'date',
    ];
}