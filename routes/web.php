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
    Route::get('/admin', [App\Http\Controllers\AdminsController::class, 'index'])->name('adminDashboard');
    Route::get('/admin/create', [App\Http\Controllers\AdminsController::class, 'adminCreate'])->name('adminCreate');
    Route::get('/admin/view', [App\Http\Controllers\AdminsController::class, 'adminView'])->name('adminView');
    Route::get('/admin/view/{table}', [App\Http\Controllers\AdminsController::class, 'show'])->name('adminViewTable');
    Route::get('/admin/view/search/{table}/{text?}', [App\Http\Controllers\AdminsController::class, 'search'])->name('AdminViewTableSearch');
    Route::get('/admin/payment', [App\Http\Controllers\AdminsController::class, 'adminPayment'])->name('adminPayment');
    Route::get('/admin/settings', [App\Http\Controllers\AdminsController::class, 'adminSettings'])->name('adminSettings');
    Route::get('/admin/subjects', [App\Http\Controllers\AdminsController::class, 'adminSubjects'])->name('adminSubjects');
});

// APPLICANT protected routes 

Route::middleware([App\Http\Middleware\ProtectApplicantRoutesMiddleware::class])->group(function () {
    Route::get('/applicant', [App\Http\Controllers\ApplicantsController::class, 'index'])->name('applicantDashboard');
});

// STUDENT protected routes 
Route::middleware([App\Http\Middleware\ProtectApplicantRoutesMiddleware::class])->group(function () {
    Route::get('/student', [App\Http\Controllers\StudentsController::class, 'index'])->name('studentDashboard');
});

// FACULTY protected routes 
Route::middleware([App\Http\Middleware\ProtectApplicantRoutesMiddleware::class])->group(function () {
    Route::get('/faculty', [App\Http\Controllers\FacultiesController::class, 'index'])->name('facultyDashboard');
});




Route::get('/dashboard', [App\Http\Controllers\DshboardController::class, 'index'])->name('home');



