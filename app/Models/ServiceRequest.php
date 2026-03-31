<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'reference_code',
    'request_date',
    'department_code',
    'request_category',
    'application_system_name',
    'expected_completion_date',
    'expected_completion_time',
    'contact_last_name',
    'contact_first_name',
    'contact_middle_name',
    'contact_suffix_name',
    'office',
    'address',
    'landline',
    'fax_no',
    'mobile_no',
    'email_address',
    'description_request',
    'description_photos',
    'status',
    'pending_at',
    'checking_at',
    'approved_at',
    'rejected_at',
    'completed_at',
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
            'expected_completion_date' => 'date',
            'approved_date' => 'date',
            'kmits_date' => 'date',
            'description_photos' => 'array',
            'action_logs' => 'array',
            'noted_by_date_signed' => 'date',
            'pending_at' => 'datetime',
            'checking_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
