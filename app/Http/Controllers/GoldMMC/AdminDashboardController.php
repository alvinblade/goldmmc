<?php

namespace App\Http\Controllers\GoldMMC;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class AdminDashboardController extends Controller
{
    public function dashboard(Request $request): View|Application|Factory|App
    {
        $selectedCompany = Company::query()->find(session('selected_company_id'));

        return view('admin.dashboard', compact('selectedCompany'));
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
