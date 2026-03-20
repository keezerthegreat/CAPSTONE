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
        'civil_status',
        'purok',
        'requestor',
        'certificate_type',
        'purpose',
        'body_content',
        'or_number',
        'amount',
        'date_issued',
    ];
}
