<?php

namespace App\Http\Controllers\GoldMMC\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Company\Warehouse\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $warehouses = Warehouse::query()
            ->where('company_id', $companyId)
            ->when(request()->filled('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                });
            })
            ->paginate(10);

        return view('admin.warehouses.index', compact('warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('warehouses', 'name')
                ->where('company_id', $companyId)]
        ]);

        Warehouse::query()->create([
            'name' => $request->input('name'),
            'company_id' => $companyId,
        ]);

        toast('Anbar yaradıldı', 'success');

        return redirect()->route('admin.warehouses.index');
    }

    public function update(Request $request, $warehouse): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('warehouses', 'name')
                ->where('company_id', $companyId)
                ->ignore($warehouse)]
        ]);

        $warehouse = Warehouse::query()->where('company_id', $companyId)->find($warehouse);

        if (!$warehouse) {
            toast('Anbar tapılmadı', 'error');
            return redirect()->back();
        }

        $warehouse->update([
            'name' => $request->input('name'),
            'company_id' => $companyId,
        ]);

        toast('Anbar yeniləndi', 'success');

        return redirect()->route('admin.warehouses.index');
    }

    public function destroy($warehouse): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $warehouse = Warehouse::query()
            ->where('company_id', $companyId)
            ->find($warehouse);

        if (!$warehouse) {
            toast('Anbar tapılmadı', 'error');
            return redirect()->back();
        }

        $warehouse->delete();

        toast('Anbar silindi', 'success');

        return redirect()->route('admin.warehouses.index');
    }
}
