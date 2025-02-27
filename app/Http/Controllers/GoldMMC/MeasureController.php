<?php

namespace App\Http\Controllers\GoldMMC;

use App\Http\Controllers\Controller;
use App\Models\Measure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MeasureController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $measures = Measure::query()
            ->when(request()->filled('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                });
            })
            ->paginate(10);

        return view('admin.measures.index', compact('measures'));
    }

    public function store(Request $request): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:measures,name'],
        ]);

        Measure::query()->create([
            'name' => $request->input('name')
        ]);

        toast('Ölçü vahidi yaradıldı', 'success');

        return redirect()->route('admin.measures.index');
    }

    public function update(Request $request, $measure): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('measures', 'name')
                ->ignore($measure)]
        ]);

        $measure = Measure::query()->find($measure);

        if (!$measure) {
            toast('Ölçü vahidi tapılmadı', 'error');
            return redirect()->back();
        }

        $measure->update([
            'name' => $request->input('name')
        ]);

        toast('Ölçü vahidi yeniləndi', 'success');

        return redirect()->route('admin.measures.index');
    }

    public function destroy($measure): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $measure = Measure::query()
            ->find($measure);

        if (!$measure) {
            toast('Ölçü vahidi tapılmadı', 'error');
            return redirect()->back();
        }

        $measure->delete();

        toast('Ölçü vahidi silindi', 'success');

        return redirect()->route('admin.measures.index');
    }
}
