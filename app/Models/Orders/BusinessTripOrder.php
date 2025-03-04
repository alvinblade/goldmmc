<?php

namespace App\Models\Orders;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessTripOrder extends Model
{
    protected $guarded = [];

    protected $casts = [
        'generated_file' => 'array',
        'company_id' => 'integer',
        'employee_id' => 'integer',
        'backup_of_logs' => 'array',
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
