<?php

namespace App\Models\Company\Invoice;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectronInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'total' => 'decimal:2',
        'e_invoice_files' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function electronInvoiceItems(): HasMany
    {
        return $this->hasMany(ElectronInvoiceItem::class, 'electron_invoice_id');
    }
}
