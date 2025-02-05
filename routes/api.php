<?php

use App\Models\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-company-employees/{companyId}', function ($companyId) {
    $company = Company::query()->with(['employees'])->find($companyId);

    if (!$company) {
        return response()->json([
            'message' => "Şirkət tapılmadı"
        ], 404);
    }

    return response()->json([
        'employees' => $company->employees->only(['id', 'name', 'surname', 'father_name'])
    ], 200);
})->name('get-company-employees');
