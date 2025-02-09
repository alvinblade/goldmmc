<?php

namespace App\Http\Requests\Orders\AwardOrder;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AwardOrderStoreRequest extends FormRequest
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
            'order_date' => ['required', 'date'],
            'main_part_of_order' => ['required'],
            'worker_infos' => ['required', 'array'],
            'worker_infos.*.position' => ['required', 'string', 'max:255'],
            'worker_infos.*.salary' => ['required', 'numeric'],
        ];
    }
}
