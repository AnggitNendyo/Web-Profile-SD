<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nisn',
        'nis',
        'name',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'address',
        'parent_name',
        'parent_phone',
        'enrollment_year',
        'status',
        'photo',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];
}
