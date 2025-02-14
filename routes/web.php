<?php

use App\Http\Controllers\GoldMMC\ActivityCodeController;
use App\Http\Controllers\GoldMMC\AdminDashboardController;
use App\Http\Controllers\GoldMMC\AttendanceLogConfigController;
use App\Http\Controllers\GoldMMC\AttendanceLogController;
use App\Http\Controllers\GoldMMC\CompanyController;
use App\Http\Controllers\GoldMMC\CurrencyController;
use App\Http\Controllers\GoldMMC\Employees\EmployeeController;
use App\Http\Controllers\GoldMMC\Employees\PositionController;
use App\Http\Controllers\GoldMMC\EnvelopeController;
use App\Http\Controllers\GoldMMC\Orders\AwardOrderController;
use App\Http\Controllers\GoldMMC\Orders\BusinessTripOrderController;
use App\Http\Controllers\GoldMMC\Orders\DefaultHolidayOrderController;
use App\Http\Controllers\GoldMMC\Orders\HiringOrderController;
use App\Http\Controllers\GoldMMC\Orders\IllnessOrderController;
use App\Http\Controllers\GoldMMC\Orders\MotherhoodOrderController;
use App\Http\Controllers\GoldMMC\Orders\PregnantOrderController;
use App\Http\Controllers\GoldMMC\RentalContractController;
use App\Http\Controllers\GoldMMC\Users\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'add_company_header'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/select-company', [AdminDashboardController::class, 'selectCompanyView'])
        ->name('admin.select-company');
    Route::post('/select-company', [AdminDashboardController::class, 'selectCompany'])
        ->name('admin.select-company.select');
    Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies.index');

    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('admin.employees.index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
        Route::post('/create', [EmployeeController::class, 'store'])->name('admin.employees.store');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
        Route::put('/{employee}/edit', [EmployeeController::class, 'update'])->name('admin.employees.update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');
        Route::post('/excel-import', [EmployeeController::class, 'importExcel'])->name('admin.employees.excel_import');
        Route::get('/download/excel', [EmployeeController::class,
            'downloadExcel'])->name('admin.employees.excel_download');
    });

    Route::prefix('positions')->group(function () {
        Route::get('/', [PositionController::class, 'index'])->name('admin.positions.index');
        Route::post('/', [PositionController::class, 'store'])->name('admin.positions.store');
        Route::put('/{position}/edit', [PositionController::class, 'update'])->name('admin.positions.update');
        Route::delete('/{position}', [PositionController::class, 'destroy'])->name('admin.positions.destroy');
    });

    Route::prefix('activity-codes')->group(function () {
        Route::get('/', [ActivityCodeController::class, 'index'])->name('admin.activityCodes.index');
        Route::post('/', [ActivityCodeController::class, 'store'])->name('admin.activityCodes.store');
        Route::put('/{activityCode}/edit', [ActivityCodeController::class, 'update'])
            ->name('admin.activityCodes.update');
        Route::delete('/{activityCode}', [ActivityCodeController::class, 'destroy'])
            ->name('admin.activityCodes.destroy');
    });

    Route::prefix('rental-contracts')->group(function () {
        Route::get('/', [RentalContractController::class, 'index'])->name('admin.rentalContracts.index');
        Route::get('/create', [RentalContractController::class, 'create'])->name('admin.rentalContracts.create');
        Route::post('/', [RentalContractController::class, 'store'])->name('admin.rentalContracts.store');
        Route::get('/{rentalContract}', [RentalContractController::class, 'show'])->name('admin.rentalContracts.show');
        Route::get('/{rentalContract}/edit', [RentalContractController::class, 'edit'])
            ->name('admin.rentalContracts.edit');
        Route::post('/{rentalContract}/edit', [RentalContractController::class, 'update'])
            ->name('admin.rentalContracts.update');
        Route::delete('/{rentalContract}', [RentalContractController::class, 'destroy'])
            ->name('admin.rentalContracts.destroy');
    });

    Route::prefix('users')->middleware(['role:leading_expert|department_head'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::post('/{user}/edit', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    Route::prefix('envelopes')->group(function () {
        Route::get('/', [EnvelopeController::class, 'index'])->name('admin.envelopes.index');
        Route::get('/create', [EnvelopeController::class, 'create'])->name('admin.envelopes.create');
        Route::post('/', [EnvelopeController::class, 'store'])->name('admin.envelopes.store');
        Route::get('/{envelope}', [EnvelopeController::class, 'show'])->name('admin.envelopes.show');
        Route::get('/{envelope}/edit', [EnvelopeController::class, 'edit'])->name('admin.envelopes.edit');
        Route::post('/{envelope}/edit', [EnvelopeController::class, 'update'])->name('admin.envelopes.update');
        Route::delete('/{envelope}', [EnvelopeController::class, 'destroy'])->name('admin.envelopes.destroy');
    });

    Route::prefix('companies')->middleware(['role:leading_expert|department_head'])->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('admin.companies.index');
        Route::get('/create', [CompanyController::class, 'create'])->name('admin.companies.create');
        Route::post('/', [CompanyController::class, 'store'])->name('admin.companies.store');
        Route::get('/{company}', [CompanyController::class, 'show'])->name('admin.companies.show');
        Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('admin.companies.edit');
        Route::post('/{company}/edit', [CompanyController::class, 'update'])->name('admin.companies.update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('admin.companies.destroy');
    });

    Route::prefix('attendance-log-configs')->group(function () {
        Route::get('/', [AttendanceLogConfigController::class, 'index'])
            ->name('admin.attendanceLogConfigs.index');
        Route::get('/create', [AttendanceLogConfigController::class, 'create'])
            ->name('admin.attendanceLogConfigs.create');
        Route::post('/', [AttendanceLogConfigController::class, 'store'])
            ->name('admin.attendanceLogConfigs.store');
        Route::get('/{attendanceLogConfig}', [AttendanceLogConfigController::class, 'show'])
            ->name('admin.attendanceLogConfigs.show');
        Route::get('/{attendanceLogConfig}/edit', [AttendanceLogConfigController::class, 'edit'])
            ->name('admin.attendanceLogConfigs.edit');
        Route::post('/{attendanceLogConfig}/edit', [AttendanceLogConfigController::class, 'update'])
            ->name('admin.attendanceLogConfigs.update');
        Route::delete('/{attendanceLogConfig}', [AttendanceLogConfigController::class, 'destroy'])
            ->name('admin.attendanceLogConfigs.destroy');
    });

    Route::prefix('attendance-logs')->group(function () {
        Route::get('/', [AttendanceLogController::class, 'index'])
            ->name('admin.attendanceLogs.index');
        Route::get('/create', [AttendanceLogController::class, 'create'])
            ->name('admin.attendanceLogs.create');
        Route::post('/', [AttendanceLogController::class, 'store'])
            ->name('admin.attendanceLogs.store');
        Route::get('/{attendanceLog}', [AttendanceLogController::class, 'show'])
            ->name('admin.attendanceLogs.show');
        Route::get('/{attendanceLog}/edit', [AttendanceLogController::class, 'edit'])
            ->name('admin.attendanceLogs.edit');
        Route::post('/{attendanceLog}/edit', [AttendanceLogController::class, 'update'])
            ->name('admin.attendanceLogs.update');
        Route::delete('/{attendanceLog}', [AttendanceLogController::class, 'destroy'])
            ->name('admin.attendanceLogs.destroy');
    });

    Route::prefix('award-orders')->group(function () {
        Route::get('/', [AwardOrderController::class, 'index'])
            ->name('admin.awardOrders.index');
        Route::get('/create', [AwardOrderController::class, 'create'])
            ->name('admin.awardOrders.create');
        Route::post('/', [AwardOrderController::class, 'store'])
            ->name('admin.awardOrders.store');
        Route::get('/{awardOrder}', [AwardOrderController::class, 'show'])
            ->name('admin.awardOrders.show');
        Route::get('/{awardOrder}/edit', [AwardOrderController::class, 'edit'])
            ->name('admin.awardOrders.edit');
        Route::post('/{awardOrder}/edit', [AwardOrderController::class, 'update'])
            ->name('admin.awardOrders.update');
        Route::delete('/{awardOrder}', [AwardOrderController::class, 'destroy'])
            ->name('admin.awardOrders.destroy');
    });

    Route::prefix('hiring-orders')->group(function () {
        Route::get('/', [HiringOrderController::class, 'index'])->name('admin.hiringOrders.index');
        Route::get('/create', [HiringOrderController::class, 'create'])->name('admin.hiringOrders.create');
        Route::post('/', [HiringOrderController::class, 'store'])->name('admin.hiringOrders.store');
        Route::delete('/{hiringOrder}', [HiringOrderController::class, 'destroy'])
            ->name('admin.hiringOrders.destroy');
    });

    Route::prefix('default-holiday-orders')->group(function () {
        Route::get('/', [DefaultHolidayOrderController::class, 'index'])->name('admin.defaultHolidayOrders.index');
        Route::get('/create', [DefaultHolidayOrderController::class, 'create'])
            ->name('admin.defaultHolidayOrders.create');
        Route::post('/', [DefaultHolidayOrderController::class, 'store'])->name('admin.defaultHolidayOrders.store');
        Route::delete('/{defaultHolidayOrder}', [DefaultHolidayOrderController::class, 'destroy'])
            ->name('admin.defaultHolidayOrders.destroy');
    });

    Route::prefix('business-trip-orders')->group(function () {
        Route::get('/', [BusinessTripOrderController::class, 'index'])->name('admin.businessTripOrders.index');
        Route::get('/create', [BusinessTripOrderController::class, 'create'])
            ->name('admin.businessTripOrders.create');
        Route::post('/', [BusinessTripOrderController::class, 'store'])->name('admin.businessTripOrders.store');
        Route::delete('/{businessTripOrder}', [BusinessTripOrderController::class, 'destroy'])
            ->name('admin.businessTripOrders.destroy');
    });

    Route::prefix('illness-orders')->group(function () {
        Route::get('/', [IllnessOrderController::class, 'index'])
            ->name('admin.illnessOrders.index');
        Route::get('/create', [IllnessOrderController::class, 'create'])
            ->name('admin.illnessOrders.create');
        Route::post('/', [IllnessOrderController::class, 'store'])->name('admin.illnessOrders.store');
        Route::delete('/{illnessOrder}', [IllnessOrderController::class, 'destroy'])
            ->name('admin.illnessOrders.destroy');
    });

    Route::prefix('motherhood-orders')->group(function () {
        Route::get('/', [MotherhoodOrderController::class, 'index'])
            ->name('admin.motherhoodOrders.index');
        Route::get('/create', [MotherhoodOrderController::class, 'create'])
            ->name('admin.motherhoodOrders.create');
        Route::post('/', [MotherhoodOrderController::class, 'store'])->name('admin.motherhoodOrders.store');
        Route::delete('/{motherhoodOrder}', [MotherhoodOrderController::class, 'destroy'])
            ->name('admin.motherhoodOrders.destroy');
    });

    Route::prefix('pregnant-orders')->group(function () {
        Route::get('/', [PregnantOrderController::class, 'index'])
            ->name('admin.pregnantOrders.index');
        Route::get('/create', [PregnantOrderController::class, 'create'])
            ->name('admin.pregnantOrders.create');
        Route::post('/', [PregnantOrderController::class, 'store'])->name('admin.pregnantOrders.store');
        Route::delete('/{pregnantOrder}', [PregnantOrderController::class, 'destroy'])
            ->name('admin.pregnantOrders.destroy');
    });
});

require __DIR__ . '/auth.php';
