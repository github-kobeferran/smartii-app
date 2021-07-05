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

Route::get('/institute', function () {
    return view('institute');
});
Route::get('/visionandmission', function () {
    return view('vission_mission');
});
Route::get('/history', function () {
    return view('history');
});
Route::get('/contactus', function () {
    return view('contact');
});


Route::get('/admissionhelp', function () {
    return view('admission_guidelines');
});
Route::get('/admissionhelp', function () {
    return view('admission_guidelines');
});



Route::get('/shsprograms', function () {
    return view('shs');
});
Route::get('/collegeprograms', function () {
    return view('college');
});



Auth::routes(['verify' => true]);


// ADMIN protected routes 
Route::middleware([App\Http\Middleware\ProtectAdminRoutesMiddleware::class])->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminsController::class, 'index'])->name('adminDashboard')->middleware(['admin.superadmin']);
    
    Route::get('/admin/create', [App\Http\Controllers\AdminsController::class, 'adminCreate'])->name('adminCreate')->middleware(['admin.registrar']);
    Route::post('/admin/create/student', [App\Http\Controllers\StudentsController::class, 'store'])->name('studentCreate');    
    Route::post('/admin/create/program', [App\Http\Controllers\ProgramsController::class, 'store'])->name('programStore');    
    Route::post('/admin/create/subject', [App\Http\Controllers\SubjectsController::class, 'store'])->name('subjectStore');    
    Route::post('/admin/create/admin', [App\Http\Controllers\AdminsController::class, 'store'])->name('adminStore');    
    Route::post('/admin/create/faculty', [App\Http\Controllers\FacultiesController::class, 'store'])->name('facultyStore');    
    Route::post('/admin/create/class', [App\Http\Controllers\StudentClassesController::class, 'store'])->name('classStore');    
    Route::post('/admin/create/room', [App\Http\Controllers\RoomsController::class, 'store'])->name('roomStore');    
    Route::post('/admin/create/invoice', [App\Http\Controllers\InvoicesController::class, 'store'])->name('createInvoice');    

    Route::get('/admin/view', [App\Http\Controllers\AdminsController::class, 'adminView'])->name('adminView');
    Route::get('/admin/pendingstudentclass/{dept}/{prog}/{subj}', [App\Http\Controllers\SubjectsTakenController::class, 'pendingStudentClass'])->name('adminPendingStudentClass');
    Route::get('/admin/view/{table}', [App\Http\Controllers\AdminsController::class, 'showTable'])->name('adminViewTable');    
    Route::get('/admin/view/{table}/{id}', [App\Http\Controllers\AdminsController::class, 'showData'])->name('adminViewData');    
    Route::get('/admin/view/{table}/{by}/{value}/{all?}', [App\Http\Controllers\AdminsController::class, 'showTableBy'])->name('adminViewTableBy');    
    Route::get('/admin/view/{table}/{dept}/{deptvalue}/{prog}/{progvalue}', [App\Http\Controllers\AdminsController::class, 'showTableByTwo'])->name('adminViewTableByTwo');
    Route::get('/admin/view/{table}/{department}/{departmentvalue}/{program}/{programvalue}/{level}/{levelvalue}/{semester}/{semestervalue}', [App\Http\Controllers\AdminsController::class, 'showTableByFour'])->name('adminViewTableByFour');
    Route::get('/admin/available/rooms/{from}/{until}/{day?}', [App\Http\Controllers\RoomsController::class, 'availableRooms'])->name('availableRooms');    
    Route::get('/admin/available/faculty/{from}/{until}/{day?}', [App\Http\Controllers\FacultiesController::class, 'availableFaculty'])->name('availableFaculty');    
    Route::get('/admin/availablerooms/{from}/{until}/{day}/{exceptid}', [App\Http\Controllers\RoomsController::class, 'availableRoomsExcept'])->name('availableRoomsExcept');    
    Route::get('/admin/availablefaculty/{from}/{until}/{day}/{exceptid}', [App\Http\Controllers\FacultiesController::class, 'availableFacultyExcept'])->name('availableFacultyExcept');    

    Route::get('/admin/search/{table}/{text?}/{dept?}', [App\Http\Controllers\AdminsController::class, 'search'])->name('AdminSearch');    
    Route::get('/admin/searchby/{table}/{by}/{value}/{text?}/', [App\Http\Controllers\AdminsController::class, 'searchBy'])->name('AdminSearchBy');    
    

    Route::post('/admin/update/room/', [App\Http\Controllers\RoomsController::class, 'update'])->name('updateRoom');
    Route::post('/admin/update/setting/', [App\Http\Controllers\SettingsController::class, 'update'])->name('updateSetting');
    Route::post('/admin/approvereq/{reqType}/{id}', [App\Http\Controllers\AdminsController::class, 'approveReq'])->name('approveReq');
    Route::post('/admin/requestreq/{reqType}/{id}', [App\Http\Controllers\AdminsController::class, 'approveReq'])->name('approveReq');


    Route::get('/admin/delete/room/{id}', [App\Http\Controllers\RoomsController::class, 'destroy'])->name('deleteRoom');

    Route::get('/admin/payment', [App\Http\Controllers\AdminsController::class, 'adminPayment'])->name('adminPayment')->middleware(['admin.accounting']);
    Route::get('/admin/settings', [App\Http\Controllers\AdminsController::class, 'adminSettings'])->name('adminSettings')->middleware(['admin.superadmin']);
    
    Route::get('/admin/classes', [App\Http\Controllers\AdminsController::class, 'adminClasses'])->name('adminClasses')->middleware(['admin.registrar']);
    Route::get('/admin/schedules/{prog}/{subj}', [App\Http\Controllers\SubjectsTakenController::class, 'showClassSchedules'])->name('showClassSchedules');

    Route::get('/admin/download/{type}/{filename}', [App\Http\Controllers\AdminsController::class, 'download'])->name('AdminDownload');
    Route::post('/admin/requestupload', [App\Http\Controllers\AdminsController::class, 'requestFileResubmission'])->name('requestFileResubmission');
    Route::post('/admin/approveapplicant', [App\Http\Controllers\AdminsController::class, 'approveApplicant'])->name('approveApplicant');

    Route::get('/admin/paymentrequests', [App\Http\Controllers\AdminsController::class, 'viewPaymentRequests'])->name('viewPaymentRequests')->middleware(['admin.accounting']);
    Route::post('/admin/approvepaymentrequest', [App\Http\Controllers\AdminsController::class, 'approvePaymentRequest'])->name('approvePaymentRequest');
    Route::any('/updateschedule', [App\Http\Controllers\AdminsController::class, 'updateSchedule'])->name('updateschedule');
    Route::any('/enrolltosubject', [App\Http\Controllers\AdminsController::class, 'enrollToSubject'])->name('enrolltosubject');
    Route::any('/updatesubject', [App\Http\Controllers\SubjectsController::class, 'update'])->name('updatesubject');
    Route::any('/updateprereq', [App\Http\Controllers\SubjectsPreReqController::class, 'updatePreReq'])->name('updateprereq');
    Route::any('/updateprogram', [App\Http\Controllers\ProgramsController::class, 'update'])->name('updateProgram');
    Route::any('/deletesubject/{id}', [App\Http\Controllers\SubjectsController::class, 'destroy'])->name('destroySubject');
    Route::any('/createannouncement', [App\Http\Controllers\AnnouncementsController::class, 'store'])->name('storeAnnouncement');
    Route::any('/deleteannouncement/{id}', [App\Http\Controllers\AnnouncementsController::class, 'delete'])->name('storeAnnouncement');

});

// APPLICANT protected routes 
Route::middleware([App\Http\Middleware\ProtectApplicantRoutesMiddleware::class])->group(function () {    

    Route::get('/admissionform', [App\Http\Controllers\ApplicantsController::class, 'form'])->name('admissionForm')->middleware(['verified', 'applicant.new']);
    Route::get('/appstatus', [App\Http\Controllers\ApplicantsController::class, 'status'])->name('appStatus')->middleware(['verified', 'applicant.submitted']);
    Route::get('/applicant/view/programs/{dept}', [App\Http\Controllers\ApplicantsController::class, 'showPrograms'])->name('applicantViewPrograms');    
    Route::get('/applicant/programs/{prog}', [App\Http\Controllers\ApplicantsController::class, 'getProg'])->name('getApplicantProg');    
    Route::any('/applicant/create/', [App\Http\Controllers\ApplicantsController::class, 'store'])->name('applicantStore');    
    Route::any('/applicant/resubmit/', [App\Http\Controllers\ApplicantsController::class, 'resubmit'])->name('applicantResubmit');    
    
});

// STUDENT protected routes 
Route::middleware([App\Http\Middleware\ProtectStudentRoutesMiddleware::class])->group(function () {

    Route::get('/student/classes/', [App\Http\Controllers\StudentsController::class, 'getClasses'])->name('studentClasses');    
    Route::get('/student/balance/', [App\Http\Controllers\StudentsController::class, 'getBalance'])->name('studentBalance');    
    Route::get('/student/createpayment/', [App\Http\Controllers\PaymentRequestsController::class, 'create'])->name('createPaymentRequest');
    Route::get('/enroll/{id}/', [App\Http\Controllers\StudentsController::class, 'getSubjectsForNextSemester'])->name('getSubjectsForNextSem');
    Route::any('/student/request/payment', [App\Http\Controllers\PaymentRequestsController::class, 'store'])->name('storePaymentRequest');    
    Route::any('/studentenroll', [App\Http\Controllers\StudentsController::class, 'enroll'])->name('studentenroll');    
    Route::any('/updatestudent', [App\Http\Controllers\StudentsController::class, 'update'])->name('studentudpate');    

});

// about student but can be accessed by all
Route::get('/studentprofile/{id?}/', [App\Http\Controllers\StudentsController::class, 'index'])->name('studentProfile')->middleware('member');

// FACULTY protected routes 
Route::middleware([App\Http\Middleware\ProtectFacultyRoutesMiddleware::class])->group(function () {
    Route::get('/myclasses', [App\Http\Controllers\FacultiesController::class, 'getClasses'])->name('facultyClasses');
    Route::get('/myclass/{id}/', [App\Http\Controllers\FacultiesController::class, 'getClass'])->name('facultyClass');
    Route::get('/sortclass/{classid}/{facultyid}/{sortby}', [App\Http\Controllers\StudentClassesController::class, 'sortStudents'])->name('sortStudents');
    Route::any('/faculty/updaterating/', [App\Http\Controllers\SubjectsTakenController::class, 'updateRating'])->name('updaterating');
    Route::any('/archiveclass', [App\Http\Controllers\StudentClassesController::class, 'archiveClass'])->name('archiveclass');
    Route::get('/facultydetails/{id?}', [App\Http\Controllers\FacultiesController::class, 'show'])->name('facultydetails');
    Route::get('/showfacultydetail/{id}/{detail}', [App\Http\Controllers\FacultiesController::class, 'showDetail'])->name('showdetail');
    Route::any('/updatefaculty', [App\Http\Controllers\FacultiesController::class, 'update'])->name('updatefaculty');
});




