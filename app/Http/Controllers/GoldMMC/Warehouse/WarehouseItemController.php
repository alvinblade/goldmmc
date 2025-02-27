<?php

namespace App\Http\Controllers\GoldMMC\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Company\Warehouse\Warehouse;
use App\Models\Company\Warehouse\WarehouseItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseItemController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $items = WarehouseItem::query()
            ->with(['warehouse'])
            ->where('company_id', $companyId)
            ->when(request()->filled('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%')
                        ->orWhere('code', 'like', '%' . request('search') . '%');
                });
            })
            ->paginate(10);

        return view('admin.warehouseItems.index', compact('items'));
    }

    public function destroy($warehouseItem): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $item = WarehouseItem::query()
            ->where('company_id', $companyId)
            ->find($warehouseItem);

        if (!$item) {
            toast('Anbar malı tapılmadı', 'error');
            return redirect()->back();
        }

        $item->delete();

        toast('Anbar malı silindi', 'success');

        return redirect()->route('admin.warehouseItems.index');
    }
}
