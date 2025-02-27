<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('electron_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('electron_invoice_id');
            $table->string('code');
            $table->string('name');
            $table->unsignedBigInteger('measure_id')->nullable();
            $table->float('quantity');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->nullable();
            $table->float('excise_tax_rate')->default(0);
            $table->decimal('excise_tax_amount')->default(0);
            $table->decimal('total_price_with_excise', 10, 2)->nullable();
            $table->decimal('vat_involved', 10, 2)->default(0);
            $table->decimal('vat_not_involved', 10, 2)->default(0);
            $table->decimal('vat_released', 10, 2)->default(0);
            $table->decimal('vat_involved_with_zero_rate', 10, 2)->default(0);
            $table->decimal('total_vat', 10, 2)->nullable();
            $table->decimal('road_tax', 10, 2)->nullable();
            $table->decimal('final_amount', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('electron_invoice_id')->references('id')
                ->on('electron_invoices')->onDelete('cascade');
            $table->foreign('measure_id')->references('id')
                ->on('measures')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electron_invoice_items');
    }
};
