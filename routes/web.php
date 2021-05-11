<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});
Route::get('/home', function () {
    return view('home');
});

Auth::routes();

// ADMIN protected routes 
Route::middleware([App\Http\Middleware\ProtectAdminRoutesMiddleware::class])->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('adminDashboard');
    Route::get('/admin/create', [App\Http\Controllers\AdminController::class, 'adminCreate'])->name('adminCreate');
    Route::get('/admin/view', [App\Http\Controllers\AdminController::class, 'adminView'])->name('adminView');
    Route::get('/admin/payment', [App\Http\Controllers\AdminController::class, 'adminPayment'])->name('adminPayment');
    Route::get('/admin/settings', [App\Http\Controllers\AdminController::class, 'adminSettings'])->name('adminSettings');
    Route::get('/admin/subjects', [App\Http\Controllers\AdminController::class, 'adminSubjects'])->name('adminSubjects');
});

// APPLICANT protected routes 

Route::middleware([App\Http\Middleware\ProtectApplicantRoutesMiddleware::class])->group(function () {
    Route::get('/applicant', [App\Http\Controllers\ApplicantController::class, 'index'])->name('applicantDashboard');
});

// STUDENT protected routes 
Route::middleware([App\Http\Middleware\ProtectApplicantRoutesMiddleware::class])->group(function () {
    Route::get('/student', [App\Http\Controllers\StudentController::class, 'index'])->name('studentDashboard');
});

// FACULTY protected routes 
Route::middleware([App\Http\Middleware\ProtectApplicantRoutesMiddleware::class])->group(function () {
    Route::get('/faculty', [App\Http\Controllers\FacultyController::class, 'index'])->name('facultyDashboard');
});




Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');



