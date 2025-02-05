<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'remember_token'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function companyAsDirector(): HasOne
    {
        return $this->hasOne(Company::class, 'director_id');
    }

    public function companyAsMainEmployee(): HasOne
    {
        return $this->hasOne(Company::class, 'main_employee_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
