<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard.dashboard');
    })->name('dashboard');

    Route::get('employees', function () {
        return view('admin.layouts.employees.index');
    })->name('employees.index');
    Route::get('employees/create', function () {
        return view('admin.layouts.employees.create');
    })->name('employees.create');
});
