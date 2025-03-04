<?php

use App\Enums\EducationTypesEnum;
use App\Enums\EmployeeTypes;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('father_name')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('position_id')->nullable();
            $table->date('birth_date');
            $table->string('id_card_serial')->unique();
            $table->string('fin_code')->unique();
            $table->date('id_card_date');
            $table->unsignedBigInteger('ssn');
            $table->date('start_date_of_employment');
            $table->date('end_date_of_employment')->nullable();
            $table->text('previous_job')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->float('work_experience')->nullable();
            $table->enum('education', EducationTypesEnum::toArray());
            $table->enum('gender', GenderTypes::toArray());
            $table->float('salary')->nullable();
            $table->date('salary_card_expired_at')->nullable();
            $table->enum('employee_type', EmployeeTypes::toArray());
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('company_id')->references('id')
                ->on('companies')->onDelete('cascade');
            $table->foreign('position_id')->references('id')
                ->on('positions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
