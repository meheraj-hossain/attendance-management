<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\EmployeeController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\ReportController;

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

        Route::get('reports/monthly-attendance/{monthName}', [
            ReportController::class, 'monthly_attendance'
        ])->name('reports.monthly.attendance');

        Route::get('reports/daily-attendance/{monthName}', [
            ReportController::class, 'daily_attendance'
        ])->name('reports.daily.attendance');

        Route::get('reports/user-attendance', [
            ReportController::class, 'user_attendance'
        ])->name('reports.user.attendance');
    });
});
