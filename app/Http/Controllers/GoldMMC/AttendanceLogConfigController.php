<?php

namespace App\Http\Controllers\GoldMMC;

use App\Enums\AttendanceLogConfigDayTypes;
use App\Enums\MonthsLocale;
use App\Http\Controllers\Controller;
use App\Models\Company\AttendanceLogConfig;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceLogConfigController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $attendanceLogConfigs = AttendanceLogConfig::query()
            ->where('company_id', $companyId)
            ->when($request->filled('search'),
                fn($query) => $query->where('year', $request->input('search')))
            ->paginate(10);

        return view('admin.attendanceLogConfigs.index', compact('attendanceLogConfigs'));
    }

    public function create(Request $request): View
    {
        $request->validate([
            'year' => ['required', 'integer',
                Rule::unique('attendance_log_configs', 'year')->where('company_id', getHeaderCompanyId()),
                'between:2000,2100'],
        ]);

        $year = $request->input('year');

        $config = collect(range(1, 12))->map(function ($month) use ($year) {
            return [
                'month' => $month,
                'month_name' => Carbon::createFromDate($year, $month, 1)
                    ->translatedFormat('F'),
                'days' => collect(range(1, Carbon::createFromDate($year, $month, 1)
                    ->daysInMonth))->map(function ($day) use ($year, $month) {
                    $date = Carbon::createFromDate($year, $month, $day);

                    return [
                        'day' => $day,
                        'day_name' => $date->translatedFormat('D'),
                        'status' => in_array($date->dayOfWeek,
                            [CarbonInterface::SATURDAY, CarbonInterface::SUNDAY]) ? 'REST_DAY' : 8
                    ];
                })->toArray(),
            ];
        });

        return view('admin.attendanceLogConfigs.create', compact('config'));
    }

    public function store(Request $request): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $request->validate([
            'year' => ['required', 'integer', 'unique:attendance_log_configs,year', 'between:2000,2080'],
            'config' => ['required', 'array'],
            'config.*.month' => ['required', 'integer', 'between:1,12',
                Rule::unique('attendance_log_configs', 'year')
                    ->where('year', $request->input('year'))
                    ->where('company_id', $companyId)
            ],
            'config.*.days' => ['required', 'array'],
            'config.*.days.*.day' => ['required', 'integer'],
            'config.*.days.*.status' => ['required', 'in:' . AttendanceLogConfigDayTypes::toString()]
        ]);

        $yearConfig = collect(range(1, 12))->map(function ($month) use ($request) {
            return [
                'days' => collect(range(1, intval(Carbon::createFromDate($request->input('year'),
                    $month, 1)->daysInMonth)))->map(function ($day) {
                    return [
                        'day' => $day,
                    ];
                })->toArray(),
                'month' => $month,
                'month_name' => Carbon::createFromDate($request->input('year'),
                    $month, 1)->isoFormat('MMMM'),
            ];
        })->toArray();

        $checkUnique = checkMonthDaysUnique($yearConfig, $request->input('config'));

        if (gettype($checkUnique) == 'string') {
            toast('Zəhmət olmasa ' . $checkUnique . ' ayını düzgün daxil edin', 'info');
            return redirect()->back();
        }

        $carbonDate = Carbon::create($request->input('year'),
            $request->input('config')[0]['month'], 1);

        $generatedConfig = [];

        foreach ($request->input('config') as $configDetail) {
            $monthWorkHours = 0;

            foreach ($configDetail['days'] as $day) {
                $monthWorkHours += intval($day['status']);
            }

            $generatedConfig[] = [
                'days' => $configDetail['days'],
                'month' => $configDetail['month'],
                'month_name' => $configDetail['month_name'],
                'month_work_hours' => $monthWorkHours
            ];
        }

        AttendanceLogConfig::query()->create([
            'company_id' => $companyId,
            'year' => $request->input('year'),
            'log_date' => $carbonDate->format('Y-m-d'),
            'config' => $generatedConfig,
        ]);

        toast("Tabel şablonu uğurla əlavə olundu", 'success');

        return redirect()->route('admin.attendanceLogConfigs.index');
    }

    public function show($attendanceLogConfig): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $attendanceLogConfig = AttendanceLogConfig::query()
            ->where('company_id', $companyId)
            ->with('company:id,company_name')
            ->find($attendanceLogConfig);

        if (!$attendanceLogConfig) {
            toast('Tabel şablonu tapılmadı', 'error');
            return redirect()->back();
        }

        return view('admin.attendanceLogConfigs.show', compact('attendanceLogConfig'));
    }

    public function update(Request $request, $attendanceLogConfig): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", "error");
            return redirect()->back();
        }

        $attendanceLogConfig = AttendanceLogConfig::query()
            ->where('company_id', $companyId)
            ->find($attendanceLogConfig);

        if (!$attendanceLogConfig) {
            toast("Tabel şablonu tapılmadı", "error");
            return redirect()->back();
        }

        $request->validate([
            'year' => ['required', 'integer', 'between:2000,2080',
                Rule::unique('attendance_log_configs', 'year')->ignore($attendanceLogConfig->id)],
            'config' => ['required', 'array'],
            'config.*.month' => ['required', 'integer', 'between:1,12',
                Rule::unique('attendance_log_configs', 'year')
                    ->where('year', $request->input('year'))
                    ->where('company_id', $request->input('company_id'))
                    ->ignore($attendanceLogConfig->id),
            ],
            'config.*.month_name' => ['required', 'string', 'in:' . MonthsLocale::toString()],
            'config.*.days' => ['required', 'array'],
            'config.*.days.*.day' => ['required', 'integer'],
            'config.*.days.*.status' => ['required', 'in:' . AttendanceLogConfigDayTypes::toString()]
        ]);

        $yearConfig = collect(range(1, 12))->map(function ($month) use ($request) {
            return [
                'days' => collect(range(1, intval(Carbon::createFromDate($request->input('year'),
                    $month, 1)->daysInMonth)))->map(function ($day) {
                    return [
                        'day' => $day,
                    ];
                })->toArray(),
                'month' => $month,
                'month_name' => Carbon::createFromDate($request->input('year'),
                    $month, 1)->isoFormat('MMMM'),
            ];
        })->toArray();

        $checkUnique = checkMonthDaysUnique($yearConfig, $request->input('config'));

        if (gettype($checkUnique) == 'string') {
            toast('Zəhmət olmasa ' . $checkUnique . ' ayını düzgün daxil edin', 'info');
            return redirect()->back();
        }

        $carbonDate = Carbon::create($request->input('year'), $request->input('config')[0]['month'], 1);

        $generatedConfig = [];

        foreach ($request->input('config') as $configDetail) {
            $monthWorkHours = 0;

            foreach ($configDetail['days'] as $day) {
                $monthWorkHours += intval($day['status']);
            }

            $generatedConfig[] = [
                'days' => $configDetail['days'],
                'month' => $configDetail['month'],
                'month_name' => $configDetail['month_name'],
                'month_work_hours' => $monthWorkHours
            ];
        }

        $attendanceLogConfig->update([
            'company_id' => $companyId,
            'year' => $request->input('year'),
            'log_date' => $carbonDate->format('Y-m-d'),
            'config' => $generatedConfig,
        ]);

        toast('Tabel şablonu uğurla yeniləndi', 'success');

        return redirect()->route('admin.attendanceLogConfigs.index');
    }

    public function destroy($attendanceLogConfig): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $attendanceLogConfig = AttendanceLogConfig::query()
            ->where('company_id', $companyId)
            ->find($attendanceLogConfig);

        if (!$attendanceLogConfig) {
            toast('Tabel şablonu tapılmadı', 'error');
            return redirect()->back();
        }

        $attendanceLogConfig->delete();

        toast('Tabel şablonu uğurla silindi', 'success');

        return redirect()->route('admin.attendanceLogConfigs.index');
    }
}
