<?php

namespace App\Http\Middleware;

use App\Models\Company\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCompanyHeader
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $excludedRoutes = [
            'admin/select-company',
        ];

        if (in_array($request->path(), $excludedRoutes)) {
            return $next($request);
        }

        if (session()->has('selected_company_id')) {
            $companyId = session('selected_company_id');
            $company = null;
            $servedCompanies = null;

            if (auth()->user()->hasRole('accountant')) {
                $servedCompanies = auth()->user()->companiesServed->pluck('company_name', 'id')->toArray();
                $company = auth()->user()->companiesServed()->find($companyId);
            }

            if (auth()->user()->hasAnyRole('leading_expert', 'department_head')) {
                $servedCompanies = Company::query()->pluck('company_name', 'id')->toArray();
                $company = Company::query()->find($companyId);
            }

            if (!$company) {
                auth()->logout();
            }

            view()->share([
                'servedCompany' => $company,
                'servedCompanies' => $servedCompanies,
            ]);

            $request->headers->set('company_id', $company->id);
        } else {
            return redirect()->to('/admin/select-company');
        }

        return $next($request);
    }
}
