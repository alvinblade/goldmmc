<?php

use App\Enums\GenderTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('motherhood_holiday_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('company_name')->nullable();
            $table->unsignedBigInteger('tax_id_number')->nullable();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('father_name')->nullable();
            $table->string('position')->nullable();
            $table->enum('gender', GenderTypes::toArray());
            $table->date('holiday_start_date')->nullable();
            $table->date('holiday_end_date')->nullable();
            $table->date('employment_start_date')->nullable();
            $table->text('type_of_holiday')->nullable();
            $table->longText('main_part_of_order')->nullable();
            $table->jsonb('generated_file')->nullable();
            $table->string('d_name')->nullable();
            $table->string('d_surname')->nullable();
            $table->string('d_father_name')->nullable();
            $table->jsonb('backup_of_logs')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motherhood_holiday_orders');
    }
};
