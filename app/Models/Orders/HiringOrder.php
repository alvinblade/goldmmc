<?php

namespace App\Models\Orders;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HiringOrder extends Model
{
    protected $guarded = [];

    protected $casts = [
        'generated_file' => 'array',
        'salary' => 'float',
        'employee_id' => 'integer',
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
