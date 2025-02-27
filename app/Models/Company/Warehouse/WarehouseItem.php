<?php

namespace App\Models\Company\Warehouse;

use App\Models\Measure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function measure(): BelongsTo
    {
        return $this->belongsTo(Measure::class, 'measure_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
