<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'certificate_no',
        'resident_name',
        'civil_status',
        'purok',
        'requestor',
        'certificate_type',
        'purpose',
        'body_content',
        'or_number',
        'amount',
        'issued_date',
    ];
}
