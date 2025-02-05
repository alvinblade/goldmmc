<?php

namespace App\Http\Controllers\GoldMMC\Employees;

use App\Http\Controllers\Controller;
use App\Models\Company\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PositionController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $positions = Position::query()
            ->where('company_id', $companyId)
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->paginate(10);

        return view('admin.positions.index', compact('positions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('positions', 'name')
                ->where('company_id', $companyId)]
        ]);

        Position::query()->create([
            'name' => $request->input('name'),
            'company_id' => $companyId,
        ]);

        toast('Vəzifə yaradıldı', 'success');

        return redirect()->route('admin.positions.index');
    }

    public function update(Request $request, $position): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('positions', 'name')
                ->where('company_id', $companyId)
                ->ignore($position)]
        ]);

        $position = Position::query()->where('company_id', $companyId)->find($position);

        if (!$position) {
            toast('Vəzifə tapılmadı', 'error');
            return redirect()->back();
        }

        $position->update([
            'name' => $request->input('name'),
            'company_id' => $companyId,
        ]);

        toast('Vəzifə yeniləndi', 'success');

        return redirect()->route('admin.positions.index');
    }

    public function destroy($position): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $position = Position::query()
            ->where('company_id', $companyId)
            ->find($position);

        if (!$position) {
            toast('Vəzifə tapılmadı', 'error');
            return redirect()->back();
        }

        $position->delete();

        toast('Vəzifə silindi', 'success');

        return redirect()->route('admin.positions.index');
    }
}
