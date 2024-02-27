<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\EmployeeController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\ReportController;
use \App\Http\Controllers\PdfController;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/unauthorized', function () {
    $data['title'] = 'Unauthorized';
    return view('admin.layouts.errors.unauthorized', $data);
})->name('unauthorized');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard.dashboard');
    })->name('dashboard');

    Route::middleware('role:' . \App\Models\User::ROLE_SUPER_ADMIN . ',' . \App\Models\User::ROLE_ADMIN)->group(function () {
        Route::resource('users', UserController::class);
        Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');

        Route::get('reports/monthly-attendance', [
            ReportController::class, 'monthly_attendance'
        ])->name('reports.monthly.attendance');

        Route::get('reports/monthly-attendance-date-wise', [
            ReportController::class, 'monthly_attendance_date_wise'
        ])->name('reports.monthly.attendance.date.wise');

        Route::get('reports/daily-attendance', [
            ReportController::class, 'daily_attendance'
        ])->name('reports.daily.attendance');

        Route::get('reports/user-attendance', [
            ReportController::class, 'user_attendance'
        ])->name('reports.user.attendance');

        Route::get('reports/monthly-attendance2', [
            ReportController::class, 'monthly_attendance2'
        ])->name('reports.monthly.attendance2');
        Route::get('reports/generate-pdf', [PdfController::class, 'generate_pdf'])->name('generate-pdf');
        Route::get('reports/generate-monthly-attendance-date-wise-pdf', [PdfController::class, 'generate_monthly_attendance_date_wise_pdf'])->name('generate-monthly-attendance-date-wise-pdf');
    });
});
