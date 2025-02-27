<?php

namespace App\Models\Company;

use App\Models\Company\Invoice\ElectronInvoice;
use App\Models\Contract\RentalContract;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $guarded = [];

    protected $casts = [
        'company_emails' => 'array',
        'charter_files' => 'array',
        'tax_id_number_files' => 'array',
        'extract_files' => 'array',
        'creators_files' => 'array',
        'director_id_card_files' => 'array',
        'founding_decision_files' => 'array',
        'fixed_asset_files' => 'array',
        'director_id' => 'integer',
        'main_user_id' => 'integer',
        'accountant_id' => 'integer',
        'fixed_asset_files_exists' => 'boolean',
        'is_vat_payer' => 'boolean'
    ];

    public function mainEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'main_employee_id');
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'director_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'company_id');
    }

    public function accountant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class, 'company_id');
    }

    public function activityCodes(): HasMany
    {
        return $this->hasMany(ActivityCode::class, 'company_id');
    }

    public function rentalContracts(): HasMany
    {
        return $this->hasMany(RentalContract::class, 'company_id');
    }

    public function electronInvoices(): HasMany
    {
        return $this->hasMany(ElectronInvoice::class, 'company_id');
    }
}
