<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    protected $fillable = [
        'name',
        'description',
        'photo',
        'coach',
        'schedule_day',
        'schedule_time',
        'category',
        'sort_order',
    ];
}
