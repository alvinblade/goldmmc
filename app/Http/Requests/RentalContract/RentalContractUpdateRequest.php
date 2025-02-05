<?php

namespace App\Http\Requests\RentalContract;

use App\Enums\RentalTypes;
use App\Enums\UserTypesEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RentalContractUpdateRequest extends FormRequest
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
            'object_name' => ['required', 'string', 'max:255'],
            'object_code' => ['nullable', 'string', 'max:255',
                'unique:rental_contracts,object_code,' . $this->rentalContract],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'rental_area' => ['required', 'string', 'max:255'],
            'rental_price' => ['required', 'numeric'],
            'rental_price_with_vat' => ['nullable', 'numeric'],
            'is_vat' => ['nullable', 'boolean'],
            'tenant_type' => ['nullable', 'in:' . UserTypesEnum::toString()],
            'contract_files' => ['nullable', 'array'],
            'contract_files.*' => ['nullable', 'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,docx,doc', 'max:4096'],
            'type' => ['required', 'in:' . RentalTypes::toString()],
            'address' => ['nullable', 'string'],
        ];
    }
}
