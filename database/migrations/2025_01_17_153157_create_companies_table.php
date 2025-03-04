<?php

use App\Enums\CompanyCategoriesEnum;
use App\Enums\CompanyObligationsEnum;
use App\Enums\UserTypesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name'); // Şirkətin adı
            $table->string('company_short_name'); // Şirkətin qısa adı
            $table->enum('company_category', CompanyCategoriesEnum::toArray()); // Şirkət kateqoriyası
            $table->enum('company_obligation', CompanyObligationsEnum::toArray()); // Şirkət mükəlləfiyyəti
            $table->enum('owner_type', UserTypesEnum::toArray()); // Fiziki yaxud Hüquqi
            $table->jsonb('company_emails')->default(json_encode([])); // Şirkət e-poçtları
            $table->longText('company_address')->nullable(); // Şirkət ünvanı
            $table->unsignedBigInteger('tax_id_number'); // VÖEN
            $table->date('tax_id_number_date'); // VÖEN alınma tarixi
            $table->unsignedBigInteger('dsmf_number'); // DSMF üçot nömrəsi
            $table->unsignedBigInteger('main_employee_id')->nullable(); // Səlahiyyətli şəxs
            $table->unsignedBigInteger('director_id')->nullable(); // Direktor
            $table->unsignedBigInteger('accountant_id')->nullable(); // Mühasib
            $table->date('accountant_assign_date')->nullable(); // Mühasibin səlahiyyət tarixi
            $table->jsonb('tax_id_number_files')->default(json_encode([])); // VÖEN faylları
            $table->jsonb('charter_files')->default(json_encode([])); // Nizamnamə faylları
            $table->jsonb('extract_files')->default(json_encode([])); // Çıxarış faylları
            $table->jsonb('director_id_card_files')->default(json_encode([])); // Direktorun ŞV faylları
            $table->jsonb('creators_files')->default(json_encode([])); // Təsisçi faylları
            $table->boolean('fixed_asset_files_exists')->default(false); // Mülkiyyətində olan əsas vəsaitlər bool
            $table->jsonb('fixed_asset_files')
                ->default(json_encode([])); // Mülkiyyətində olan əsas vəsaitlərin faylları
            $table->jsonb('founding_decision_files')->default(json_encode([])); // Təsisçi qərarı faylları
            $table->string('asan_sign'); // ASAN imza mobil nömrəsi
            $table->date('asan_sign_start_date'); // ASAN imza başlama vaxtı
            $table->date('asan_sign_expired_at'); // ASAN imza bitmə vaxtı
            $table->string('asan_id'); // ID
            $table->unsignedInteger('pin1'); // PİN1
            $table->unsignedInteger('pin2'); // PİN2
            $table->unsignedInteger('puk'); // PUK
            $table->unsignedInteger('statistic_code'); // Statistika kodu
            $table->string('statistic_password'); // Statistika şifrəsi
            $table->string('operator_azercell_account'); // Operator kabineti hesabı (Azercell)
            $table->string('operator_azercell_password'); // Operator kabineti parolu
            $table->string('ydm_account_email')->nullable(); // YDM hesabı elektron poçtu
            $table->string('ydm_password')->nullable(); // YDM hesabı şifrəsi
            $table->date('ydm_card_expired_at')->nullable(); // YDM kartının bitiş tarixi
            $table->boolean('is_vat_payer')->default(false); // ƏDV ödəyicisidir?
            $table->timestamps();

            $table->foreign('accountant_id')->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
