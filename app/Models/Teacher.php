<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'position',
        'subject',
        'nip',
        'photo',
        'sort_order',
    ];
}
