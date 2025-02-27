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
        Schema::create('warehouse_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('measure_id');
            $table->string('name');
            $table->string('code');
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price')->default(0);
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')
                ->on('warehouses')->onDelete('cascade');
            $table->foreign('measure_id')->references('id')
                ->on('measures')->onDelete('cascade');
            $table->foreign('company_id')->references('id')
                ->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_items');
    }
};
