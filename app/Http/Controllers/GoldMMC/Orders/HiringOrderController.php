<?php

namespace App\Http\Controllers\GoldMMC\Orders;

use App\Enums\AttendanceLogDayTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\HiringOrder\HiringOrderStoreRequest;
use App\Models\Company\AttendanceLog;
use App\Models\Company\AttendanceLogConfig;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Orders\HiringOrder;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

class HiringOrderController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $hiringOrders = HiringOrder::query()
            ->where('company_id', $companyId)
            ->with('company')
            ->when($request->input('search'), function ($query) use ($request) {
                return $query->where('order_number', 'like', '%' . $request->input('search') . '%');
            })
            ->paginate(10);

        return view('admin.hiringOrders.index', compact('hiringOrders'));
    }

    public function create(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $employees = Employee::query()
            ->with('position')
            ->where('company_id', $companyId)
            ->get();

        return view('admin.hiringOrders.create', compact('employees'));
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function store(HiringOrderStoreRequest $request): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $data = $request->validated();

        $company = $this->getCompany($companyId);
        $companyName = $company->company_name;
        $employee = Employee::query()->with(['position'])->find($data['employee_id']);

        $orderNumber = generateOrderNumber(HiringOrder::class, $company->company_short_name);
        $startDate = Carbon::parse($request->input('start_date'))->format('d.m.Y');
        $char = substr($startDate, '-2');
        $lastChar = getNumberEnd($char);
        $gender = !empty($employee->gender) ? getGender($employee->gender) : "MALE";

        $data = array_merge($data, [
            'position' => $employee->position?->name,
            'salary_in_words' => getNumberAsWords($data['salary']),
            'order_number' => $orderNumber,
            'last_char' => $lastChar,
            'company_name' => $companyName,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'father_name' => $employee->father_name,
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'gender' => $gender,
            'start_date' => $startDate,
            'tax_id_number' => $company->tax_id_number,
            'company_id' => $companyId
        ]);

        $year = Carbon::parse($request->input('start_date'))->format('Y');
        $month = Carbon::parse($request->input('start_date'))->format('n');
        $day = Carbon::parse($request->input('start_date'))->format('j');

        $attendanceLogConfig = AttendanceLogConfig::query()
            ->where('company_id', $companyId)
            ->where('year', $year)
            ->first();

        if (!$attendanceLogConfig) {
            toast('Tabel şablonu mövcud deyil', 'error');
            return redirect()->back();
        }

        $attendanceLog = AttendanceLog::query()
            ->where('company_id', $attendanceLogConfig->company_id)
            ->where('year', $attendanceLogConfig->year)
            ->where('employee_id', $request->input('employee_id'))
            ->first();

        if ($attendanceLog) {
            toast('İşçi artıq tabelə əlavə olunub', 'info');
            return redirect()->back();
        }

        $config = $attendanceLogConfig->config;

        $generatedConfig = [];

        for ($i = 0; $i < count($config); $i++) {
            for ($j = 0; $j < count($config[$i]['days']); $j++) {
                $generatedConfig[$i]['month'] = $config[$i]['month'];
                $generatedConfig[$i]['month_name'] = $config[$i]['month_name'];
                $generatedConfig[$i]['month_work_hours'] = $config[$i]['month_work_hours'];

                if ($i + 1 == $month) {
                    if ($day > $config[$i]['days'][$j]['day']) {
                        $generatedConfig[$i]['days'][$j] = [
                            'day' => $config[$i]['days'][$j]['day'],
                            'status' => AttendanceLogDayTypes::NULL_DAY->value
                        ];
                    } else {
                        $generatedConfig[$i]['days'][$j] = [
                            'day' => $config[$i]['days'][$j]['day'],
                            'status' => $config[$i]['days'][$j]['status']
                        ];
                    }
                } elseif ($i + 1 < $month) {
                    $generatedConfig[$i]['days'][$j] = [
                        'day' => $config[$i]['days'][$j]['day'],
                        'status' => AttendanceLogDayTypes::NULL_DAY->value
                    ];
                } else {
                    $generatedConfig[$i]['days'][$j] = [
                        'day' => $config[$i]['days'][$j]['day'],
                        'status' => $config[$i]['days'][$j]['status']
                    ];
                }
            }
        }

        foreach ($generatedConfig as $value) {
            $countMonthWorkDayHours = getMonthWorkDayHours($value['days']);
            $countCelebrationRestDays = getCelebrationRestDaysCount($value['days']);
            $countMonthWorkDays = getMonthWorkDaysCount($value['days']);

            AttendanceLog::query()
                ->create([
                    'company_id' => $attendanceLogConfig->company_id,
                    'employee_id' => $request->input('employee_id'),
                    'year' => $attendanceLogConfig->year,
                    'month' => $value['month'],
                    'salary' => $request->input('salary'),
                    'days' => $value['days'],
                    'month_work_hours' => $value['month_work_hours'],
                    'month_work_days' => $countMonthWorkDays,
                    'celebration_days' => $countCelebrationRestDays,
                    'month_work_day_hours' => $countMonthWorkDayHours,
                    'start_date' => Carbon::createFromDate($year, $month, 1),
                    'end_date' => Carbon::createFromDate($year, $month, Carbon::createFromDate($year, $month, 1)
                        ->daysInMonth),
                ]);
        }

        $documentPath = public_path('assets/order_templates/HIRING.docx');
        $fileName = 'HIRING_ORDER_' . Str::slug($companyName . $orderNumber, '_') . '.docx';
        $filePath = public_path('assets/hiring_orders/' . $fileName);

        $templateProcessor = new TemplateProcessor($documentPath);
        $this->templateProcessor($templateProcessor, $filePath, $data);

        $hiringOrder = HiringOrder::query()->create([
            'order_number' => $orderNumber,
            'company_id' => $companyId,
            'employee_id' => $request->input('employee_id'),
            'company_name' => $companyName,
            'tax_id_number' => $company->tax_id_number,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'father_name' => $employee->father_name,
            'gender' => $employee->gender,
            'start_date' => $request->input('start_date'),
            'position' => $employee->position?->name,
            'salary' => $request->input('salary'),
            'salary_in_words' => getNumberAsWords($request->input('salary')),
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
        ]);

        $generatedFilePath = returnOrderFile('assets/hiring_orders/' . $fileName,
            $fileName, 'hiring_orders');

        $hiringOrder->update([
            'generated_file' => $generatedFilePath
        ]);

        $employee->update(['salary' => $request->input('salary')]);

        toast("İşə götürmə sənədi yaradıldı", "success");

        return redirect()->route('admin.hiringOrders.index');
    }

    private function getCompany($companyId): Builder|array|Collection|Model
    {
        return Company::query()->with(['mainEmployee', 'director'])->find($companyId);
    }

    private function templateProcessor(TemplateProcessor $templateProcessor, $filePath, $data): void
    {
        $templateProcessor->setValue('order_number', $data['order_number']);
        $templateProcessor->setValue('company_name', $data['company_name']);
        $templateProcessor->setValue('company_tax_id_number', $data['tax_id_number']);
        $templateProcessor->setValue('position', $data['position']);
        $templateProcessor->setValue('salary', $data['salary']);
        $templateProcessor->setValue('salary_in_words', $data['salary_in_words']);
        $templateProcessor->setValue('start_date', $data['start_date'] . $data['last_char']);
        $templateProcessor->setValue('name', $data['name']);
        $templateProcessor->setValue('surname', $data['surname']);
        $templateProcessor->setValue('father_name', $data['father_name']);
        $templateProcessor->setValue('gender', $data['gender']);
        $templateProcessor->setValue('d_surname', $data['d_surname']);
        $templateProcessor->setValue('d_name', $data['d_name']);
        $templateProcessor->setValue('d_father_name', $data['d_father_name']);
        $templateProcessor->saveAs($filePath);
    }

    public function destroy($hiringOrder): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $hiringOrder = HiringOrder::query()
            ->where('company_id', $companyId)
            ->find($hiringOrder);

        if (!$hiringOrder) {
            toast('İşə götürmə sənədi tapılmadı', 'error');
            return redirect()->back();
        }

        AttendanceLog::query()
            ->where('company_id', $companyId)
            ->where('employee_id', $hiringOrder->employee_id)
            ->delete();

        if (!empty($hiringOrder->generated_file[0]['path'])) {
            $filePath = public_path($hiringOrder->generated_file[0]['path']);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $hiringOrder->delete();

        toast('İşə götürmə əmri uğurla silindi', 'success');

        return redirect()->route('admin.hiringOrders.index');
    }
}
