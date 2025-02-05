<?php

namespace App\Http\Controllers\GoldMMC;

use App\Http\Controllers\Controller;
use App\Http\Requests\RentalContract\RentalContractStoreRequest;
use App\Http\Requests\RentalContract\RentalContractUpdateRequest;
use App\Models\Company\Company;
use App\Models\Contract\RentalContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentalContractController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $rentalContracts = RentalContract::query()
            ->where('company_id', $companyId)
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('object_name', 'like', '%' . $request->search . '%')
                        ->orWhere('object_code', 'like', '%' . $request->search . '%')
                        ->orWhere('rental_area', 'like', '%' . $request->search . '%')
                        ->orWhere('rental_price', 'like', '%' . $request->search . '%')
                        ->orWhere('rental_price_with_vat', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', '=', $request->type);
            })
            ->paginate(10);

        return view('admin.rentalContracts.index', compact('rentalContracts'));
    }

    public function create(): View
    {
        return view('admin.rentalContracts.create');
    }

    public function store(RentalContractStoreRequest $request): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $company = Company::query()->where('id', '=', $companyId)->first();

        $data = $request->validated();

        $data = array_merge($data,
            [
                'company_id' => $companyId,
                'tenant_type' => $company->owner_type,
                'creator_id' => auth()->id(),
                'is_vat' => $company->is_vat_payer
            ]
        );

        if ($request->hasFile('contract_files')) {
            $contractFiles = $request->file('contract_files');
            $data = array_merge($data, ['contract_files' => returnFilesArray($contractFiles, 'contract_files')]);
        }

        if ($company->is_vat_payer) {
            $rentalPriceWithVAT = ($request->input('rental_price') * 0.18);
            $data = array_merge($data, ['rental_price_with_vat' => $rentalPriceWithVAT]);
        }

        RentalContract::query()->create($data);

        toast("İcarə müqaviləsi yaradıldı", "success");

        return redirect()->route('admin.rentalContracts.index');
    }

    public function edit($rentalContract): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $rentalContract = RentalContract::query()
            ->where('company_id', $companyId)
            ->find($rentalContract);

        if (!$rentalContract) {
            toast("İcarə müqaviləsi tapılmadı", "error");
            return redirect()->back();
        }

        return view('admin.rentalContracts.edit', compact('rentalContract'));
    }

    public function update(RentalContractUpdateRequest $request, $rentalContract): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $company = Company::query()->where('id', '=', $companyId)->first();

        $data = $request->validated();

        $rentalContract = RentalContract::query()
            ->where('company_id', $companyId)
            ->find($rentalContract);

        if (!$rentalContract) {
            toast("İcarə müqaviləsi tapılmadı", "error");
            return redirect()->back();
        }

        $data = array_merge($data,
            [
                'company_id' => $companyId,
                'tenant_type' => $company->owner_type,
                'creator_id' => auth()->id(),
                'is_vat' => $company->is_vat_payer
            ]
        );

        if ($request->has('delete_contract_files') && $request->delete_contract_files != null) {
            $deletedRentalContractFiles = json_decode('[' . $request->input('delete_contract_files') . ']', true);
            $rentalContractFiles = $rentalContract->contract_files ?? [];
            $deletedFiles = deleteFiles($deletedRentalContractFiles, $rentalContractFiles);
            $rentalContract->contract_files = array_values($deletedFiles);
        }

        if ($request->hasFile('contract_files')) {
            $rentalContractFiles = $request->file('contract_files');
            $rentalContractFilesArr = $rentalContract->contract_files ?? [];
            $updatedFiles = returnFilesArray($rentalContractFiles, 'contract_files');
            $data = array_merge($data, ['contract_files' => array_merge($rentalContractFilesArr, $updatedFiles)]);
        }

        if ($company->is_vat_payer) {
            $rentalPriceWithVAT = ($request->input('rental_price') * 0.18);
            $data = array_merge($data, ['rental_price_with_vat' => $rentalPriceWithVAT]);
        }

        $rentalContract->update($data);

        toast("İcarə müqaviləsi yeniləndi", "success");

        return redirect()->route('admin.rentalContracts.index');
    }

    public function show($rentalContract): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $rentalContract = RentalContract::query()
            ->where('company_id', $companyId)
            ->with(['company'])
            ->find($rentalContract);

        if (!$rentalContract) {
            toast("İcarə müqaviləsi tapılmadı", "error");
            return redirect()->back();
        }

        return view('admin.rentalContracts.show', compact('rentalContract'));
    }

    public function destroy($rentalContract): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $rentalContract = RentalContract::query()
            ->where('company_id', $companyId)
            ->find($rentalContract);

        if (!$rentalContract) {
            toast("İcarə müqaviləsi tapılmadı", "error");
            return redirect()->back();
        }

        if ($rentalContract->contract_files != null && count($rentalContract->contract_files) > 0) {
            checkFilesAndDeleteFromStorage($rentalContract->contract_files);
        }

        $rentalContract->delete();

        toast("İcarə müqaviləsi silindi", "success");

        return redirect()->route('admin.rentalContracts.index');
    }
}
