<?php

namespace Database\Seeders\Users;

use App\Enums\EducationTypesEnum;
use App\Enums\EmployeeTypes;
use App\Enums\GenderTypes;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departmentHead = User::query()->create([
            'name' => 'Ziya',
            'surname' => 'Bünyadov',
            'father_name' => 'Bünyad',
            'phone' => '+994555555555',
            'email' => 'ziya@gmail.com',
            'email_verified_at' => Carbon::now(),
            'username' => 'ziyabunyadov',
            'education_files' => [],
            'certificate_files' => [],
            'cv_files' => [],
            'self_photo_files' => [],
            'birth_date' => '1969-04-10 23:59:00',
            'education' => EducationTypesEnum::COMPLETED_HIGHER->value,
            'previous_job' => 'Yoxdur',
            'account_status' => 'APPROVED',
            'password' => Hash::make('123456789'),
        ]);

        $orkhanDepartmentHead = User::query()->create([
            'name' => 'Orxan',
            'surname' => 'Məmmədov',
            'father_name' => 'Kamil',
            'phone' => '+994554660019',
            'email' => 'orkhan@gmail.com',
            'email_verified_at' => Carbon::now(),
            'username' => 'orkhan',
            'education_files' => [],
            'certificate_files' => [],
            'cv_files' => [],
            'self_photo_files' => [],
            'birth_date' => '1990-04-10 23:59:00',
            'education' => EducationTypesEnum::COMPLETED_HIGHER->value,
            'previous_job' => 'Mühasib',
            'account_status' => 'APPROVED',
            'password' => Hash::make('123456789')
        ]);

        $leadingExpert = User::query()->create([
            'name' => 'Nurlan',
            'surname' => 'Zeynallı',
            'father_name' => 'Zeynal',
            'phone' => '+994556666666',
            'email' => 'nurlan@gmail.com',
            'email_verified_at' => Carbon::now(),
            'username' => 'nurlan',
            'education_files' => [],
            'certificate_files' => [],
            'cv_files' => [],
            'self_photo_files' => [],
            'birth_date' => '1978-09-30 23:59:00',
            'education' => EducationTypesEnum::COMPLETED_HIGHER->value,
            'previous_job' => 'Kassir',
            'account_status' => 'APPROVED',
            'password' => Hash::make('123456789')
        ]);

        $accountant = User::query()->create([
            'name' => 'Ramal',
            'surname' => 'İsmayılov',
            'father_name' => 'İsmayıl',
            'phone' => '+994557777777',
            'email' => 'ramal@gmail.com',
            'email_verified_at' => Carbon::now(),
            'username' => 'ramal',
            'birth_date' => '1988-09-30 23:59:00',
            'education' => EducationTypesEnum::COMPLETED_HIGHER->value,
            'education_files' => [],
            'certificate_files' => [],
            'cv_files' => [],
            'self_photo_files' => [],
            'previous_job' => 'Baş mühasib',
            'account_status' => 'APPROVED',
            'password' => Hash::make('123456789')
        ]);

        $orkhanDepartmentHead->assignRole('department_head');
        $departmentHead->assignRole('department_head');
        $leadingExpert->assignRole('leading_expert');
        $accountant->assignRole('accountant');

        $accountant->assignCompanies([
            1, 2, 3, 4, 5, 6
        ]);

        $position1 = Position::query()->create([
            'name' => 'Baş mühəndis',
            'company_id' => 1
        ]);

        $position2 = Position::query()->create([
            'name' => 'Aparıcı mütəxəssis',
            'company_id' => 1
        ]);

        $companyEmployee1 = Employee::query()->create([
            'name' => 'Kazım',
            'surname' => 'Kazımov',
            'father_name' => 'Murad',
            'company_id' => 1,
            'position_id' => $position1->id,
            'birth_date' => '1999-09-30',
            'id_card_serial' => 'AZE8888888',
            'fin_code' => '4RYESJV',
            'id_card_date' => '2019-09-30',
            'ssn' => 1234567890123,
            'start_date_of_employment' => '2021-09-30',
            'end_date_of_employment' => null,
            'previous_job' => 'Baş mühəndis',
            'gender' => GenderTypes::MALE->value,
            'phone' => '+994501234567',
            'email' => 'bamsibeyrek@gmail.com',
            'education' => EducationTypesEnum::COMPLETED_HIGHER->value,
            'employee_type' => EmployeeTypes::DIRECTOR->value,
            'salary' => 2500,
            'salary_card_expired_at' => '2024-07-21',
        ]);

        $companyEmployee2 = Employee::query()->create([
            'name' => 'Qurban',
            'surname' => 'Qurbanov',
            'father_name' => 'Natiq',
            'company_id' => 1,
            'position_id' => $position2->id,
            'birth_date' => '1999-09-30',
            'id_card_serial' => 'AZE8888882',
            'fin_code' => '4RYESJD',
            'id_card_date' => '2019-09-30',
            'ssn' => 1234567890124,
            'start_date_of_employment' => '2021-09-30',
            'end_date_of_employment' => null,
            'previous_job' => 'Aparıcı mütəxəssis',
            'gender' => GenderTypes::MALE->value,
            'phone' => '+994501234560',
            'email' => 'kamil@gmail.com',
            'education' => EducationTypesEnum::COMPLETED_HIGHER->value,
            'employee_type' => EmployeeTypes::DIRECTOR->value,
            'salary' => 2500,
            'salary_card_expired_at' => '2024-07-21',
        ]);

        Company::query()->first()->update([
            'director_id' => $companyEmployee1->id
        ]);

        Company::query()->whereIn('id', [
            1, 2, 3, 4, 5, 6, 7, 8, 9, 10
        ])->update([
            'accountant_id' => $accountant->id
        ]);
    }
}
