<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'position',
        'subject',
        'education',
        'bio',
        'nip',
        'photo',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'education' => 'array',
        ];
    }
}
