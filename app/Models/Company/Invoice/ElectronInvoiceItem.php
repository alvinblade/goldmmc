<?php

namespace App\Models\Company\Invoice;

use App\Models\Measure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectronInvoiceItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'excise_tax_rate' => 'decimal:2',
        'excise_tax_amount' => 'decimal:2',
        'total_price_with_excise' => 'decimal:2',
        'vat_involved' => 'decimal:2',
        'vat_not_involved' => 'decimal:2',
        'vat_released' => 'decimal:2',
        'vat_involved_with_zero_rate' => 'decimal:2',
        'total_vat' => 'decimal:2',
        'road_tax' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function electronInvoice(): BelongsTo
    {
        return $this->belongsTo(ElectronInvoice::class, 'electron_invoice_id');
    }

    public function measure(): BelongsTo
    {
        return $this->belongsTo(Measure::class, 'measure_id');
    }
}
