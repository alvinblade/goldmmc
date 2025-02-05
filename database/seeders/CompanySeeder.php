<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 1; $i < 11; $i++) {
            $k = $i + 1;
            Company::query()->create([
                'company_name' => "Company $i Hüquqi",
                'company_short_name' => "CYH$i",
                'company_category' => 'MICRO',
                'company_obligation' => 'SIMPLIFIED',
                'company_address' => 'Baku, Azerbaijan',
                'company_emails' => ["company_legal$i@mail.ru", "company_legal$k@mail.ru"],
                'owner_type' => 'LEGAL',
                'tax_id_number' => 1234567890,
                'tax_id_number_date' => '2021-01-01',
                'dsmf_number' => 1234567890123,
                'main_employee_id' => null,
                'director_id' => null,
                'asan_sign' => '+994501234567',
                'asan_sign_start_date' => '2021-01-01',
                'asan_sign_expired_at' => '2024-07-21',
                'asan_id' => '123456789',
                'pin1' => 1234,
                'pin2' => 12345,
                'puk' => 12345678,
                'statistic_code' => 12345678,
                'statistic_password' => '12345678',
                'operator_azercell_account' => '+994501234567',
                'operator_azercell_password' => '12345678',
                'ydm_account_email' => "ydm_legal$i@mail.ru",
                'ydm_password' => '12345678',
                'ydm_card_expired_at' => '2024-07-21',
                'is_vat_payer' => true,
            ]);
        }
        for ($i = 1; $i < 30; $i++) {
            $k = $i + 1;
            Company::query()->create([
                'company_name' => "Company $i Fiziki",
                'company_short_name' => "CYF$i",
                'company_category' => 'MICRO',
                'company_obligation' => 'SIMPLIFIED',
                'company_address' => 'Baku, Azerbaijan',
                'company_emails' => ["company_individual$i@mail.ru", "company_individual$k@mail.ru"],
                'owner_type' => 'INDIVIDUAL',
                'tax_id_number' => 1234567890,
                'tax_id_number_date' => '2021-01-01',
                'dsmf_number' => 1234567890123,
                'main_employee_id' => null,
                'director_id' => null,
                'asan_sign' => '+994501234567',
                'asan_sign_start_date' => '2021-01-01',
                'asan_sign_expired_at' => '2024-07-21',
                'asan_id' => '123456789',
                'pin1' => 1234,
                'pin2' => 12345,
                'puk' => 12345678,
                'statistic_code' => 12345678,
                'statistic_password' => '12345678',
                'operator_azercell_account' => '+994501234567',
                'operator_azercell_password' => '12345678',
                'ydm_account_email' => "ydm_individual$i@mail.ru",
                'ydm_password' => '12345678',
                'ydm_card_expired_at' => '2024-07-21',
                'is_vat_payer' => false,
            ]);
        }
    }
}
