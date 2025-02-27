<?php

namespace App\Http\Controllers\GoldMMC;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function dashboard(): View|RedirectResponse
    {
        $selectedCompany = Company::query()->find(session('selected_company_id'));

        if (!$selectedCompany) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->back();
        }

        $taxIdNumberFiles = $selectedCompany->tax_id_number_files ?? [];
        $charterFiles = $selectedCompany->charter_files ?? [];
        $extractFiles = $selectedCompany->extract_files ?? [];
        $directorIdCardFiles = $selectedCompany->director_id_card_files ?? [];
        $creatorsFiles = $selectedCompany->creators_files ?? [];
        $fixedAssetFiles = $selectedCompany->fixed_asset_files ?? [];
        $foundingDecisionFiles = $selectedCompany->founding_decision_files ?? [];

        $files = [
            'taxIdNumberFiles' => [
                'title' => 'VÖEN faylları',
                'files' => $taxIdNumberFiles
            ],
            'charterFiles' => [
                'title' => 'Nizamnamə faylları',
                'files' => $charterFiles
            ],
            'extractFiles' => [
                'title' => 'Çıxarış faylları',
                'files' => $extractFiles
            ],
            'directorIdCardFiles' => [
                'title' => 'Direktorun ŞV faylları',
                'files' => $directorIdCardFiles
            ],
            'creatorsFiles' => [
                'title' => 'Təsisçi faylları',
                'files' => $creatorsFiles
            ],
            'fixedAssetFiles' => [
                'title' => 'Əsas vəsaitlərin faylları',
                'files' => $fixedAssetFiles
            ],
            'foundingDecisionFiles' => [
                'title' => 'Təsisçi qərarı faylları',
                'files' => $foundingDecisionFiles
            ]
        ];

        return view('admin.dashboard', compact('selectedCompany', 'files'));
    }

    public function selectCompanyView(): View
    {
        $companies = auth()->user()->companiesServed;

        if (auth()->user()->hasAnyRole('leading_expert', 'department_head')) {
            $companies = Company::query()->get();
        }

        return view('admin.select-company', compact('companies'));
    }

    public
    function selectCompany(Request $request)
    {
        $request->validate([
            'company_id' => ['required']
        ]);

        $company = auth()->user()->companiesServed()->find($request->company_id);

        if (auth()->user()->hasAnyRole('leading_expert', 'department_head')) {
            $company = Company::query()->find($request->company_id);
        }

        if (!$company) {
            toast("Şirkət tapılmadı", 'error');
            return redirect()->back();
        }

        session(['selected_company_id' => $company->id]);

        return redirect()->route('admin.dashboard');
    }
}
