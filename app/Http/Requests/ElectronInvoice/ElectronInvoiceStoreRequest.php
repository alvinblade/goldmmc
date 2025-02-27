<?php

namespace App\Http\Requests\ElectronInvoice;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ElectronInvoiceStoreRequest extends FormRequest
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
            'invoice_number' => ['required', 'string', 'size:10', 'regex:/^[A-Z]{4}[0-9]{6}$/',
                Rule::unique('electron_invoices', 'invoice_number')
                    ->where('company_id', getHeaderCompanyId())],
            'invoice_date' => ['required', 'date'],
            'e_invoice_files' => ['nullable', 'array'],
            'e_invoice_files.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls', 'max:2048'],
            'invoice_infos' => ['required', 'array'],
            'invoice_infos.*.name' => ['required', 'string', 'max:255'],
            'invoice_infos.*.code' => ['required', 'string', 'max:255',
                Rule::unique('electron_invoice_items', 'code')],
            'invoice_infos.*.measure_id' => ['required', 'exists:measures,id'],
            'invoice_infos.*.quantity' => ['required', 'numeric'],
            'invoice_infos.*.unit_price' => ['required', 'numeric'],
            'invoice_infos.*.excise_tax_rate' => ['required', 'numeric'],
            'invoice_infos.*.vat_involved' => ['required', 'numeric'],
            'invoice_infos.*.vat_not_involved' => ['required', 'numeric'],
            'invoice_infos.*.vat_released' => ['required', 'numeric'],
            'invoice_infos.*.vat_involved_with_zero_rate' => ['required', 'numeric'],
            'invoice_infos.*.road_tax' => ['required', 'numeric'],
        ];
    }
}
