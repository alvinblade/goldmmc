<?php

namespace App\Http\Requests\Orders\TerminationOrder;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TerminationOrderStoreRequest extends FormRequest
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
            'employee_id' => ['required', 'integer', Rule::exists('employees', 'id')
                ->where('company_id', request()->header('company-id')),
            ],
            'employment_start_date' => ['required', 'date'],
            'termination_date' => ['required', 'date'],
            'days_count' => ['required', 'integer'],
            'main_part_of_order' => ['required', 'string']
        ];
    }
}
