<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'parent_name',
        'name',
        'regcode',
        'address',
        'licensing_status',
        'license_date',
        'facility_type',
        'classification',
        'street',
        'building',
        'region',
        'province',
        'city',
        'barangay',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'license_date' => 'date',
    ];
}
