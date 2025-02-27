<?php

namespace App\Models\Company;

use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'employee_id' => 'integer',
        'company_id' => 'integer',
        'days' => 'array',
        'month_work_days' => 'integer',
        'month_work_hours' => 'integer',
        'celebration_days' => 'integer',
        'month_work_day_hours' => 'integer'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
