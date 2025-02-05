<?php

namespace App\Http\Controllers\GoldMMC;

use App\Http\Controllers\Controller;
use App\Models\Company\ActivityCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ActivityCodeController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $activityCodes = ActivityCode::query()
            ->where('company_id', $companyId)
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->paginate(10);

        return view('admin.activityCodes.index', compact('activityCodes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('activity_codes', 'name')
                ->where('company_id', $companyId)]
        ]);

        ActivityCode::query()->create([
            'name' => $request->input('name'),
            'company_id' => $companyId,
        ]);

        toast('Fəaliyyət kodu yaradıldı', 'success');

        return redirect()->route('admin.activityCodes.index');
    }

    public function update(Request $request, $activityCode): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('activity_codes', 'name')
                ->where('company_id', $companyId)
                ->ignore($activityCode)]
        ]);

        $activityCode = ActivityCode::query()->where('company_id', $companyId)->find($activityCode);

        if (!$activityCode) {
            toast('Fəaliyyət kodu tapılmadı', 'error');
            return redirect()->back();
        }

        $activityCode->update([
            'name' => $request->input('name'),
            'company_id' => $companyId,
        ]);

        toast('Fəaliyyət kodu yeniləndi', 'success');

        return redirect()->route('admin.activityCodes.index');
    }

    public function destroy($activityCode): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $activityCode = ActivityCode::query()
            ->where('company_id', $companyId)
            ->find($activityCode);

        if (!$activityCode) {
            toast('Fəaliyyət kodu tapılmadı', 'error');
            return redirect()->back();
        }

        $activityCode->delete();

        toast('Fəaliyyət kodu silindi', 'success');

        return redirect()->route('admin.activityCodes.index');
    }
}
