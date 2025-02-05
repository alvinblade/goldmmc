<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckHasCompanyHeader
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

        if (!session()->has('selected_company_id') || !$request->hasHeader('company-id')) {
            return redirect()->to('/admin/select-company');
        }

        return $next($request);
    }
}
