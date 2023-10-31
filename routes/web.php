<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\EmployeeController;
use \App\Http\Controllers\UserController;

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
    dd('You do not have access to this link');
})->name('unauthorized');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard.dashboard');
    })->name('dashboard');

    Route::middleware('role:'.\App\Models\User::ROLE_SUPER_ADMIN)->group(function () {
        Route::resource('users', UserController::class);

        Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('employees/store', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('employees/edit/{employee}', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('employees/update/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('employees/delete/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });


    Route::get('reports/daily-reports', function () {
        return view('admin.layouts.reports.daily_reports');
    })->name('reports.daily');
    Route::get('reports/monthly-reports', function () {
        return view('admin.layouts.reports.monthly_reports');
    })->name('reports.monthly');
});
