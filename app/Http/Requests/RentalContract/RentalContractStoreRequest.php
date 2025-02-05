<?php

namespace App\Http\Requests\RentalContract;

use App\Enums\RentalTypes;
use App\Enums\UserTypesEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RentalContractStoreRequest extends FormRequest
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
            'object_code' => ['nullable', 'string', 'max:255', 'unique:rental_contracts,object_code'],
            'start_date' => ['required', 'date', 'before:end_date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'rental_area' => ['required', 'string', 'max:255'],
            'rental_price' => ['required', 'numeric'],
            'rental_price_with_vat' => ['nullable', 'numeric'],
            'is_vat' => ['nullable', 'boolean'],
            'contract_files' => ['required', 'array'],
            'contract_files.*' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,docx,doc', 'max:4096'],
            'address' => ['nullable', 'string'],
            'type' => ['required', 'in:' . RentalTypes::toString()],
            'creator_id' => ['nullable', 'exists:users,id']
        ];
    }
}
