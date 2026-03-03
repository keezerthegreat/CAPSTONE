<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
  protected $fillable = [
    'full_name',
    'gender',
    'birth_date',
    'contact_number',
    'address',
    'position',
    'department',
    'date_started',
    'term_start',
    'term_end',
    'status'
];
}
