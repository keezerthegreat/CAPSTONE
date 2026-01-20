<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
   protected $fillable = [
    'certificate_no',
    'resident_name',
    'certificate_type',
    'purpose',
    'issued_date',
];

}