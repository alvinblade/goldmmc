<?php

namespace App\Http\Requests\Company;

use App\Enums\CompanyCategoriesEnum;
use App\Enums\CompanyObligationsEnum;
use App\Enums\UserTypesEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'min:3', 'max:35', 'unique:companies,company_name'],
            'company_short_name' => ['required', 'string', 'min:2', 'max:6'],
            'company_category' => ['required', 'in:' . CompanyCategoriesEnum::toString()],
            'company_obligation' => ['required', 'in:' . CompanyObligationsEnum::toString()],
            'company_address' => ['nullable', 'string', 'min:3', 'max:255'],
            'company_emails' => ['required', 'array'],
            'company_emails.*' => ['required', 'email:rfc,dns'],
            'owner_type' => ['nullable', 'in:' . UserTypesEnum::toString()],
            'tax_id_number' => ['required', 'digits:10', 'regex:/^\d{9}[12]$/'],
            'tax_id_number_date' => ['required', 'date'],
            'dsmf_number' => ['required', 'integer', 'digits:13'],
            'tax_id_number_files' => ['required', 'array'],
            'tax_id_number_files.*' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,docx,doc'],
            'charter_files' => ['required', 'array'],
            'charter_files.*' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,docx,doc'],
            'extract_files' => ['required', 'array'],
            'extract_files.*' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,docx,doc'],
            'accountant_id' => ['required', 'integer', 'exists:users,id'],
            'accountant_assign_date' => ['nullable', 'date'],
            'main_employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'director_id' => ['nullable', 'integer', 'exists:employees,id'],
            'director_id_card_files' => ['nullable', 'required_with:director_id', 'array'],
            'director_id_card_files.*' => ['nullable', 'required_with:director_id',
                'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,docx,doc'],
            'creators_files' => ['required', 'array'],
            'creators_files.*' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,docx,doc'],
            'fixed_asset_files_exists' => ['required', 'boolean'],
            'fixed_asset_files' => ['required_if:fixed_asset_files_exists,true', 'array'],
            'fixed_asset_files.*' => ['required_if:fixed_asset_files_exists,true',
                'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,docx,doc'],
            'founding_decision_files' => ['required', 'array'],
            'founding_decision_files.*' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,docx,doc'],
            'asan_sign' => ['required', 'phone:AZ'],
            'asan_sign_start_date' => ['required', 'date'],
            'asan_sign_expired_at' => ['required', 'date'],
            'asan_id' => ['required', 'string'],
            'pin1' => ['required', 'integer', 'digits:4'],
            'pin2' => ['required', 'integer', 'digits:5'],
            'puk' => ['required', 'integer', 'digits:8'],
            'statistic_code' => ['required', 'integer', 'digits:7'],
            'statistic_password' => ['required', 'string', 'max:255'],
            'operator_azercell_account' => ['required', 'phone:AZ', 'regex:/^(\+994|0)(50|51|10)\d{7}$/'],
            'operator_azercell_password' => ['required', 'string', 'max:255'],
            'ydm_account_email' => ['nullable', 'email:filter'],
            'ydm_password' => ['nullable', 'required_with:ydm_account_email,', 'string', 'max:255'],
            'ydm_card_expired_at' => ['nullable', 'required_with:ydm_account_email', 'date'],
            'is_vat_payer' => ['required', 'boolean'],
        ];
    }
}
