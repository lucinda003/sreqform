<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'reference_code',
    'request_date',
    'department_code',
    'contact_last_name',
    'contact_first_name',
    'contact_middle_name',
    'office',
    'address',
    'landline',
    'fax_no',
    'mobile_no',
    'description_request',
    'status',
    'approved_by_name',
    'approved_by_signature',
    'approved_by_position',
    'approved_date',
    'kmits_date',
    'time_received',
    'actions_taken',
    'action_logs',
    'noted_by_name',
    'noted_by_position',
    'noted_by_date_signed',
    'user_id',
])]
class ServiceRequest extends Model
{
    protected function casts(): array
    {
        return [
            'request_date' => 'date',
            'approved_date' => 'date',
            'kmits_date' => 'date',
            'action_logs' => 'array',
            'noted_by_date_signed' => 'date',
        ];
    }
}
