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

Auth::routes(['verify' => true]);
// Route::resource('students', 'App\Http\Controllers\StudentsController');

// ADMIN protected routes 
Route::middleware([App\Http\Middleware\ProtectAdminRoutesMiddleware::class])->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminsController::class, 'index'])->name('adminDashboard')->middleware('verified');
    
    Route::get('/admin/create', [App\Http\Controllers\AdminsController::class, 'adminCreate'])->name('adminCreate');
    Route::post('/admin/create/student', [App\Http\Controllers\StudentsController::class, 'store'])->name('studentCreate');    
    Route::post('/admin/create/program', [App\Http\Controllers\ProgramsController::class, 'store'])->name('programStore');    
    Route::post('/admin/create/subject', [App\Http\Controllers\SubjectsController::class, 'store'])->name('subjectStore');    
    Route::post('/admin/create/admin', [App\Http\Controllers\AdminsController::class, 'store'])->name('adminStore');    
    Route::post('/admin/create/faculty', [App\Http\Controllers\FacultiesController::class, 'store'])->name('facultyStore');    
    Route::post('/admin/create/class', [App\Http\Controllers\StudentClassesController::class, 'store'])->name('classStore');    

    Route::get('/admin/view', [App\Http\Controllers\AdminsController::class, 'adminView'])->name('adminView');
    Route::get('/admin/pendingstudentclass/{dept}/{prog}/{subj}', [App\Http\Controllers\SubjectsTakenController::class, 'pendingStudentClass'])->name('adminPendingStudentClass');
    Route::get('/admin/view/{table}', [App\Http\Controllers\AdminsController::class, 'showTable'])->name('adminViewTable');    
    Route::get('/admin/view/{table}/{id}', [App\Http\Controllers\AdminsController::class, 'showData'])->name('adminViewData');
    Route::get('/admin/search/{table}/{text?}', [App\Http\Controllers\AdminsController::class, 'search'])->name('AdminViewTableSearch');
    Route::get('/admin/view/{table}/{by}/{value}/{all?}', [App\Http\Controllers\AdminsController::class, 'showTableBy'])->name('adminViewTableBy');    
    Route::get('/admin/view/{table}/{dept}/{deptvalue}/{prog}/{progvalue}', [App\Http\Controllers\AdminsController::class, 'showTableByTwo'])->name('adminViewTableByTwo');
    Route::get('/admin/view/{table}/{department}/{departmentvalue}/{program}/{programvalue}/{level}/{levelvalue}/{semester}/{semestervalue}', [App\Http\Controllers\AdminsController::class, 'showTableByFour'])->name('adminViewTableByFour');

    Route::get('/admin/payment', [App\Http\Controllers\AdminsController::class, 'adminPayment'])->name('adminPayment');
    Route::get('/admin/settings', [App\Http\Controllers\AdminsController::class, 'adminSettings'])->name('adminSettings');
    Route::get('/admin/classes', [App\Http\Controllers\AdminsController::class, 'adminClasses'])->name('adminClasses');
});

// APPLICANT protected routes 

Route::middleware([App\Http\Middleware\ProtectApplicantRoutesMiddleware::class])->group(function () {
    Route::get('/applicant', [App\Http\Controllers\ApplicantsController::class, 'index'])->name('applicantDashboard')->middleware('verified');
});

// STUDENT protected routes 
Route::middleware([App\Http\Middleware\ProtectApplicantRoutesMiddleware::class])->group(function () {
    Route::get('/student', [App\Http\Controllers\StudentsController::class, 'index'])->name('studentDashboard')->middleware('verified');
});

// FACULTY protected routes 
Route::middleware([App\Http\Middleware\ProtectApplicantRoutesMiddleware::class])->group(function () {
    Route::get('/faculty', [App\Http\Controllers\FacultiesController::class, 'index'])->name('facultyDashboard')->middleware('verified');
});


// Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');



