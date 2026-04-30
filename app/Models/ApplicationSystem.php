<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationSystem extends Model
{
    protected $table = 'application_systems';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
