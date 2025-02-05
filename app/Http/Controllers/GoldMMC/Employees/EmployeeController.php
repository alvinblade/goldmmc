<?php

namespace App\Http\Controllers\GoldMMC\Employees;

use App\Exports\EmployeeExcelExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Imports\EmployeeExcelImport;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EmployeeController extends Controller
{
    public function index(Request $request): RedirectResponse|View|Factory|App
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $employees = Employee::query()
            ->where('company_id', $companyId)
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('surname', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%')
                        ->orWhere('father_name', 'like', '%' . $request->search . '%')
                        ->orWhere('id_card_serial', 'like', '%' . $request->search . '%')
                        ->orWhere('fin_code', 'like', '%' . $request->search . '%')
                        ->orWhere('ssn', 'like', '%' . $request->search . '%');
                });
            })
            ->paginate(10);

        return view('admin.employees.index', compact('employees'));
    }

    public function create(): View
    {
        $positions = Position::query()
            ->where('company_id', getHeaderCompanyId())
            ->get();

        return view('admin.employees.create', compact('positions'));
    }

    public function store(EmployeeStoreRequest $request): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $data = $request->validated();
        $lowerCases = array_map('strtolower', $request->only('email'));
        $data = array_merge($data, $lowerCases, ['company_id' => $companyId]);

        Employee::query()->create($data);

        toast('İşçi uğurla əlavə olundu', 'success');

        return redirect()->route('admin.employees.index');
    }

    public function edit($employee): View|RedirectResponse
    {
        $positions = Position::query()
            ->where('company_id', getHeaderCompanyId())
            ->get();

        $employee = Employee::query()
            ->where('company_id', getHeaderCompanyId())
            ->find($employee);

        if (!$employee) {
            toast('Əməkdaş tapılmadı', 'error');
            return redirect()->back();
        }

        return view('admin.employees.edit', compact('positions', 'employee'));
    }

    public function show($employee): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $employee = Employee::query()
            ->where('company_id', $companyId)
            ->with('company')
            ->find($employee);

        if (!$employee) {
            toast('Əməkdaş tapılmadı', 'error');
            return redirect()->back();
        }

        return view('admin.employees.show', compact('employee'));
    }

    public function update(EmployeeUpdateRequest $request, $employee): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $data = $request->validated();
        $lowerCases = array_map('strtolower', $request->only('email'));
        $data = array_merge($data, $lowerCases, ['company_id' => $companyId]);

        $employee = Employee::query()
            ->where('company_id', $companyId)
            ->find($employee);

        if (!$employee) {
            toast('Əməkdaş tapılmadı', 'error');
            return redirect()->back();
        }

        $employee->update($data);

        toast('Əməkdaş uğurla yeniləndi', 'success');

        return redirect()->route('admin.employees.index');
    }

    public function destroy($employee): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $employee = Employee::query()
            ->where('company_id', $companyId)
            ->find($employee);

        if (!$employee) {
            toast('Əməkdaş tapılmadı', 'error');
            return redirect()->back();
        }

        $employee->delete();

        toast('Əməkdaş uğurla silindi', 'success');

        return redirect()->route('admin.employees.index');
    }


    public function importExcel(Request $request): RedirectResponse
    {
        $request->validate([
            'excel_file' => ['required', 'file']
        ]);

        Excel::import(new EmployeeExcelImport(), $request->file('excel_file'));

        toast('İşçilər yaradıldı!', 'success');

        return redirect()->route('admin.employees.index');
    }

    public function downloadExcel(): BinaryFileResponse
    {
        return Excel::download(new EmployeeExcelExport(), 'employees_' . now() . '.xlsx',
            \Maatwebsite\Excel\Excel::XLSX);
    }
}
