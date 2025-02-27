<?php

namespace App\Http\Controllers\GoldMMC\Invoices;

use App\Http\Controllers\Controller;
use App\Http\Requests\ElectronInvoice\ElectronInvoiceStoreRequest;
use App\Http\Requests\ElectronInvoice\ElectronInvoiceUpdateRequest;
use App\Models\Company\Invoice\ElectronInvoice;
use App\Models\Company\Invoice\ElectronInvoiceItem;
use App\Models\Measure;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ElectronInvoiceController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->back();
        }

        $electronInvoices = ElectronInvoice::query()
            ->where('company_id', $companyId)
            ->with('company')
            ->when(request('search'), function ($query) {
                $query->where('invoice_number', 'like', '%' . request('search') . '%');
            })
            ->paginate(10);

        return view('admin.electronInvoices.index', compact('electronInvoices'));
    }

    public function create(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->back();
        }

        $measures = Measure::query()->get();

        return view('admin.electronInvoices.create', compact('measures'));
    }

    public function store(ElectronInvoiceStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->back();
        }

        if ($request->hasFile('e_invoice_files')) {
            $file = $request->file('e_invoice_files');
            $data = array_merge($data, ['e_invoice_files' => returnFilesArray($file, 'invoice_electron_files')]);
        }

        $totalFinalAmount = 0;

        DB::beginTransaction();

        try {
            $eInvoice = ElectronInvoice::query()->create([
                'company_id' => $companyId,
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'total' => $totalFinalAmount,
                'e_invoice_files' => $data['e_invoice_files'] ?? [],
            ]);

            foreach ($request->input('invoice_infos') as $key => $item) {
                $totalPrice = 0;
                $quantity = $request->input('invoice_infos')[$key]['quantity'];
                $unitPrice = $request->input('invoice_infos')[$key]['unit_price'];
                $totalPrice = $quantity * $unitPrice;
                $exciseTaxRate = $request->input('invoice_infos')[$key]['excise_tax_rate'];
                $exciseTaxAmount = ($totalPrice * $exciseTaxRate) / 100;
                $totalPriceWithExciseTax = $totalPrice + $exciseTaxAmount;
                $vatInvolved = $request->input('invoice_infos')[$key]['vat_involved'];
                $totalVatAmount = $vatInvolved * 0.18;
                $roadTax = $request->input('invoice_infos')[$key]['road_tax'];
                $finalTotalAmount = $totalPriceWithExciseTax + $totalVatAmount + $roadTax;

                ElectronInvoiceItem::query()->create([
                    'electron_invoice_id' => $eInvoice->id,
                    'code' => $request->input('invoice_infos')[$key]['code'],
                    'name' => $request->input('invoice_infos')[$key]['name'],
                    'measure_id' => $request->input('invoice_infos')[$key]['measure_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'excise_tax_rate' => $exciseTaxRate,
                    'excise_tax_amount' => $exciseTaxAmount,
                    'total_price_with_excise' => $totalPriceWithExciseTax,
                    'vat_involved' => $vatInvolved,
                    'vat_not_involved' => $request->input('invoice_infos')[$key]['vat_not_involved'],
                    'vat_released' => $request->input('invoice_infos')[$key]['vat_released'],
                    'vat_involved_with_zero_rate' => $request->input('invoice_infos')[$key]['vat_involved_with_zero_rate'],
                    'total_vat' => $totalVatAmount,
                    'road_tax' => $roadTax,
                    'final_amount' => $finalTotalAmount
                ]);
                $totalFinalAmount += $finalTotalAmount;
            }

            $eInvoice->update([
                'total' => $totalFinalAmount
            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            toast('Əməliyyat tamamlanmadı', 'error');
            return redirect()->back();
        }

        toast('Əməliyyat tamamlandı', 'success');
        return redirect()->route('admin.electronInvoices.index');
    }

    public function show($id): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->back();
        }

        $electronInvoice = ElectronInvoice::query()
            ->with(['electronInvoiceItems.measure'])
            ->where('company_id', '=', $companyId)
            ->find($id);

        if (!$electronInvoice) {
            toast("E-qaimə tapılmadı", 'error');
            return redirect()->back();
        }

        return view('admin.electronInvoices.show', compact('electronInvoice'));
    }

    public function edit($id): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->back();
        }

        $electronInvoice = ElectronInvoice::query()
            ->where('company_id', '=', $companyId)
            ->with(['electronInvoiceItems.measure'])
            ->find($id);

        if (!$electronInvoice) {
            toast("E-qaimə tapılmadı", 'error');
            return redirect()->back();
        }

        $measures = Measure::query()->get();

        return view('admin.electronInvoices.edit', compact('electronInvoice', 'measures'));
    }

    public function update(ElectronInvoiceUpdateRequest $request, $id): RedirectResponse
    {
        $data = $request->validated();

        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->back();
        }

        $totalFinalAmount = 0;

        DB::beginTransaction();

        $electronInvoice = ElectronInvoice::query()
            ->where('company_id', '=', $companyId)
            ->with('electronInvoiceItems')
            ->find($id);

        if (!$electronInvoice) {
            toast("E-qaimə tapılmadı", 'error');
            return redirect()->back();
        }

        if ($request->hasFile('e_invoice_files')) {
            $electronInvoiceFiles = $request->file('e_invoice_files');
            $electronInvoiceFilesArr = $electronInvoice->e_invoice_files ?? [];
            $updatedFiles = returnFilesArray($electronInvoiceFiles, 'invoice_electron_files');
            $data = array_merge($data, ['e_invoice_files' => array_merge($electronInvoiceFilesArr, $updatedFiles)]);
        }

        try {
            $electronInvoice->electronInvoiceItems()->delete();

            $electronInvoice->update([
                'company_id' => $companyId,
                'invoice_number' => $request->input('invoice_number'),
                'invoice_date' => $request->input('invoice_date'),
                'total' => $totalFinalAmount,
                'e_invoice_files' => $data['e_invoice_files'] ?? $electronInvoice->e_invoice_files,
            ]);

            if ($request->has('delete_electron_invoice_files') && $request->delete_electron_invoice_files != null) {
                $deletedElectronInvoiceFiles =
                    json_decode('[' . $request->input('delete_electron_invoice_files') . ']', true) ?? [];
                $electronInvoiceFiles = $electronInvoice->e_invoice_files ?? [];
                $deletedFiles = deleteFiles($deletedElectronInvoiceFiles, $electronInvoiceFiles);
                $electronInvoice->e_invoice_files = array_values($deletedFiles);
            }

            foreach ($request->input('invoice_infos') as $key => $item) {
                $totalPrice = 0;
                $quantity = $request->input('invoice_infos')[$key]['quantity'];
                $unitPrice = $request->input('invoice_infos')[$key]['unit_price'];
                $totalPrice = $quantity * $unitPrice;
                $exciseTaxRate = $request->input('invoice_infos')[$key]['excise_tax_rate'];
                $exciseTaxAmount = ($totalPrice * $exciseTaxRate) / 100;
                $totalPriceWithExciseTax = $totalPrice + $exciseTaxAmount;
                $vatInvolved = $request->input('invoice_infos')[$key]['vat_involved'];
                $totalVatAmount = $vatInvolved * 0.18;
                $roadTax = $request->input('invoice_infos')[$key]['road_tax'];
                $finalTotalAmount = $totalPriceWithExciseTax + $totalVatAmount + $roadTax;

                ElectronInvoiceItem::query()->create([
                    'electron_invoice_id' => $electronInvoice->id,
                    'code' => $request->input('invoice_infos')[$key]['code'],
                    'name' => $request->input('invoice_infos')[$key]['name'],
                    'measure_id' => $request->input('invoice_infos')[$key]['measure_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'excise_tax_rate' => $exciseTaxRate,
                    'excise_tax_amount' => $exciseTaxAmount,
                    'total_price_with_excise' => $totalPriceWithExciseTax,
                    'vat_involved' => $vatInvolved,
                    'vat_not_involved' => $request->input('invoice_infos')[$key]['vat_not_involved'],
                    'vat_released' => $request->input('invoice_infos')[$key]['vat_released'],
                    'vat_involved_with_zero_rate' => $request->input('invoice_infos')[$key]['vat_involved_with_zero_rate'],
                    'total_vat' => $totalVatAmount,
                    'road_tax' => $roadTax,
                    'final_amount' => $finalTotalAmount
                ]);
                $totalFinalAmount += $finalTotalAmount;
            }

            $electronInvoice->update([
                'total' => $totalFinalAmount
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            toast('Əməliyyat tamamlanmadı', 'error');
            return redirect()->back();
        }

        DB::commit();


        toast('Əməliyyat tamamlandı', 'success');
        return redirect()->route('admin.electronInvoices.index');
    }

    public function destroy($id): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->back();
        }

        $electronInvoice = ElectronInvoice::query()
            ->where('company_id', $companyId)
            ->find($id);


        if (!$electronInvoice) {
            toast('E-qaimə tapılmadı', 'error');
            return redirect()->back();
        }

        if ($electronInvoice->e_invoice_files != null && count($electronInvoice->e_invoice_files) > 0) {
            checkFilesAndDeleteFromStorage($electronInvoice->e_invoice_files);
        }

        $electronInvoice->delete();

        toast('Əməliyyat tamamlandı', 'success');
        return redirect()->route('admin.electronInvoices.index');
    }
}
