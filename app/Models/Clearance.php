<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'clearance_no',
        'resident_name',
        'certificate_type',
        'purpose',
        'date_issued',
    ];
}
