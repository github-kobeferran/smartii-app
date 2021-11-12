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


//google oauth

Route::get('auth/google', [App\Http\Controllers\GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [App\Http\Controllers\GoogleController::class, 'handleGoogleCallback']);

//-------

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

Route::get('/events', [App\Http\Controllers\EventsController::class, 'showEvents'])->name('events');
Route::get('/post/{id}', [App\Http\Controllers\PostsController::class, 'show'])->name('post.show')->middleware(['verified']);
Route::get('/posts', [App\Http\Controllers\PostsController::class, 'showAll'])->name('post.showall')->middleware(['verified']);
Route::get('/createpost', [App\Http\Controllers\PostsController::class, 'create'])->name('post.create')->middleware(['verified', 'adminAndFaculty']);
Route::any('/uploadpost', [App\Http\Controllers\PostsController::class, 'store'])->middleware(['verified', 'adminAndFaculty']);
Route::get('/editpost/{email}/{id}', [App\Http\Controllers\PostsController::class, 'edit'])->middleware(['verified', 'adminAndFaculty']);
Route::any('/updatepost', [App\Http\Controllers\PostsController::class, 'update'])->middleware(['verified', 'adminAndFaculty']);
Route::get('/invoice/{invoice_id}', [App\Http\Controllers\InvoicesController::class, 'show'])->middleware(['verified'])->name('invoice.show');    
Route::get('/cor/{student_id}', [App\Http\Controllers\SubjectsTakenController::class, 'viewCOR'])->middleware(['verified'])->name('subjectstaken.viewCOR');    

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
    Route::any('/admin/create/invoice', [App\Http\Controllers\InvoicesController::class, 'store'])->name('createInvoice');    

    Route::get('/admin/view', [App\Http\Controllers\AdminsController::class, 'adminView'])->name('adminView');
    Route::get('/admin/pendingstudentclass/{dept}/{prog}/{subj}/{sortby?}', [App\Http\Controllers\SubjectsTakenController::class, 'pendingStudentClass'])->name('adminPendingStudentClass');
    Route::get('/admin/view/{table}', [App\Http\Controllers\AdminsController::class, 'showTable'])->name('adminViewTable');    
    Route::get('/admin/view/{table}/{id}', [App\Http\Controllers\AdminsController::class, 'showData'])->name('adminViewData');    
    Route::get('/admin/view/{table}/{by}/{value}/{all?}', [App\Http\Controllers\AdminsController::class, 'showTableBy'])->name('adminViewTableBy');    
    Route::get('/admin/view/{table}/{dept}/{deptvalue}/{prog}/{progvalue}', [App\Http\Controllers\AdminsController::class, 'showTableByTwo'])->name('adminViewTableByTwo');
    Route::get('/admin/view/{table}/{department}/{departmentvalue}/{program}/{programvalue}/{level}/{levelvalue}/{semester}/{semestervalue}/{all?}', [App\Http\Controllers\AdminsController::class, 'showTableByFour'])->name('adminViewTableByFour');
    Route::get('/admin/available/rooms/{from}/{until}/{day?}', [App\Http\Controllers\RoomsController::class, 'availableRooms'])->name('availableRooms');    
    Route::get('/admin/available/faculty/{programid}/{from}/{until}/{day?}', [App\Http\Controllers\FacultiesController::class, 'availableFaculty'])->name('availableFaculty');    
    Route::get('/admin/availablerooms/{from}/{until}/{day}/{exceptid}', [App\Http\Controllers\RoomsController::class, 'availableRoomsExcept'])->name('availableRoomsExcept');    
    Route::get('/admin/availablefaculty/{from}/{until}/{day}/{exceptid}/{programid}', [App\Http\Controllers\FacultiesController::class, 'availableFacultyExcept'])->name('availableFacultyExcept');    

    //search, searchby
    Route::get('/admin/search/{table}/{text?}/{dept?}', [App\Http\Controllers\AdminsController::class, 'search'])->name('AdminSearch');    
    Route::get('/admin/searchby/{table}/{by}/{value}/{text?}/', [App\Http\Controllers\AdminsController::class, 'searchBy'])->name('AdminSearchBy');    
    

    Route::post('/admin/update/room/', [App\Http\Controllers\RoomsController::class, 'update'])->name('updateRoom');
    Route::post('/admin/update/setting/', [App\Http\Controllers\SettingsController::class, 'update'])->name('updateSetting');
    Route::post('/admin/approvereq/{reqType}/{id}', [App\Http\Controllers\AdminsController::class, 'approveReq'])->name('approveReq');
    Route::post('/admin/requestreq/{reqType}/{id}', [App\Http\Controllers\AdminsController::class, 'approveReq'])->name('approveReq');


    Route::get('/admin/delete/room/{id}', [App\Http\Controllers\RoomsController::class, 'destroy'])->name('deleteRoom');

    Route::get('/admin/payment', [App\Http\Controllers\AdminsController::class, 'adminPayment'])->name('adminPayment')->middleware(['admin.accounting']);
    Route::get('/admin/settings', [App\Http\Controllers\AdminsController::class, 'adminSettings'])->name('adminSettings')->middleware(['admin.superadmin']);
    
    Route::get('/admin/classes/', [App\Http\Controllers\StudentClassesController::class, 'view'])->name('adminClasses')->middleware(['admin.registrar']);
    Route::get('/admin/classes/archived', [App\Http\Controllers\StudentClassesController::class, 'viewArchived'])->name('viewArchived')->middleware(['admin.registrar']);
    Route::get('/admin/searcharchived/{text?}', [App\Http\Controllers\StudentClassesController::class, 'searchArchived'])->name('view.class.archived.search')->middleware(['admin.registrar']);
    Route::get('/admin/schedules/{prog}/{subj}', [App\Http\Controllers\SubjectsTakenController::class, 'showClassSchedules'])->name('showClassSchedules');

    Route::get('/admin/download/{type}/{filename}', [App\Http\Controllers\AdminsController::class, 'download'])->name('AdminDownload');
    Route::post('/admin/requestupload', [App\Http\Controllers\AdminsController::class, 'requestFileResubmission'])->name('requestFileResubmission');
    Route::post('/admin/approveapplicant', [App\Http\Controllers\ApplicantsController::class, 'approve'])->name('applicant.approve');
    Route::any('/admin/rejectapplicant', [App\Http\Controllers\ApplicantsController::class, 'reject'])->name('applicant.reject');
    Route::any('/admin/restoreapplicant', [App\Http\Controllers\ApplicantsController::class, 'restore'])->name('applicant.restore');

    //payment request
    Route::get('/admin/paymentrequests', [App\Http\Controllers\PaymentRequestsController::class, 'view'])->name('payment_request.view')->middleware(['admin.accounting']);
    Route::any('/admin/approvepaymentrequest', [App\Http\Controllers\PaymentRequestsController::class, 'approve'])->name('payment_request.approve');
    Route::any('/admin/rejectpaymentrequest', [App\Http\Controllers\PaymentRequestsController::class, 'reject'])->name('payment_request.reject');\
    
    Route::any('/updateschedule', [App\Http\Controllers\StudentClassesController::class, 'updateSchedule'])->name('updateschedule');
    Route::any('/enrolltosubject', [App\Http\Controllers\AdminsController::class, 'enrollToSubject'])->name('enrolltosubject');
    Route::any('/updatesubject', [App\Http\Controllers\SubjectsController::class, 'update'])->name('updatesubject');
    Route::any('/updateprereq', [App\Http\Controllers\SubjectsPreReqController::class, 'updatePreReq'])->name('updateprereq');
    Route::any('/updateprogram', [App\Http\Controllers\ProgramsController::class, 'update'])->name('updateProgram');
    Route::any('/deletesubject/{id}', [App\Http\Controllers\SubjectsController::class, 'destroy'])->name('destroySubject');
    Route::any('/createannouncement', [App\Http\Controllers\AnnouncementsController::class, 'store'])->name('storeAnnouncement');
    Route::any('/deleteannouncement/{id}', [App\Http\Controllers\AnnouncementsController::class, 'delete'])->name('storeAnnouncement');
    Route::any('/addfee', [App\Http\Controllers\FeesController::class, 'store'])->name('addfee');
    Route::any('/deletefee', [App\Http\Controllers\FeesController::class, 'delete'])->name('editfee');
    Route::any('/editfee', [App\Http\Controllers\FeesController::class, 'update'])->name('update');

    //students export
    Route::get('allstudents/export/', [App\Http\Controllers\StudentsController::class, 'allStudentsExport']);
    Route::get('allactivestudents/export/', [App\Http\Controllers\StudentsController::class, 'allActiveStudentsExport']);
    Route::get('advancedstudent/export/{dept}/{prog?}/{level?}', [App\Http\Controllers\StudentsController::class, 'advancedStudentsExport']);
    //invoices export
    Route::get('invoices/export', [App\Http\Controllers\InvoicesController::class, 'dailyMontlyYearlyExport']);
    Route::get('advancedinvoices/export/{month}/{year}', [App\Http\Controllers\InvoicesController::class, 'advancedExport']);

    Route::get('/events/create', [App\Http\Controllers\EventsController::class, 'create'])->name('createEvent');
    Route::any('/events/store', [App\Http\Controllers\EventsController::class, 'store']);
    Route::get('/events/delete/{id}', [App\Http\Controllers\EventsController::class, 'delete']);
    Route::any('/events/update', [App\Http\Controllers\EventsController::class, 'update']);
    
    Route::any('/homepageimage/store', [App\Http\Controllers\AdminsController::class, 'homepageImageStore']);
    Route::get('/homepageimage/delete/{id}', [App\Http\Controllers\AdminsController::class, 'homepageImageDelete']);
    Route::any('/homepageimage/update/', [App\Http\Controllers\AdminsController::class, 'homepageImageUpdate']);    

    Route::get('/togglepoststatus/{id}', [App\Http\Controllers\PostsController::class, 'togglestatus']);    
    Route::get('/featurepost/{id}', [App\Http\Controllers\PostsController::class, 'feature']);    
    Route::get('/viewprogramsfromdashboard', [App\Http\Controllers\ProgramsController::class, 'viewFromDashboard']);    
    
    Route::any('/attachdiscount', [App\Http\Controllers\StudentDiscountsController::class, 'attachToStudent'])->name('student_discounts.attach');    
    Route::any('/detachdiscount', [App\Http\Controllers\StudentDiscountsController::class, 'detachFromStudent'])->name('student_discounts.detach');    
    Route::any('/updatediscount', [App\Http\Controllers\DiscountsController::class, 'update'])->name('discount.update');    
    Route::any('/deletediscount', [App\Http\Controllers\DiscountsController::class, 'delete'])->name('discount.delete');    
    Route::any('/storediscount', [App\Http\Controllers\DiscountsController::class, 'store'])->name('discount.store');    
    
    //counts
    Route::get('/countclass/{prog}/{subj}', [App\Http\Controllers\StudentClassesController::class, 'countClasses']);
    //no forms
    Route::get('/remindapplicationform', [App\Http\Controllers\UsersController::class, 'remindToSubmit']);
    Route::any('/deletenoform', [App\Http\Controllers\UsersController::class, 'deleteNoAdmissionForms']);

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
    Route::get('/student/createpayment/', [App\Http\Controllers\PaymentRequestsController::class, 'create'])->name('payment_request.create');
    Route::get('/enroll/{id}/', [App\Http\Controllers\StudentsController::class, 'getSubjectsForNextSemester'])->name('getSubjectsForNextSem');
    Route::any('/student/request/payment', [App\Http\Controllers\PaymentRequestsController::class, 'store'])->name('storePaymentRequest');    
    Route::any('/studentenroll', [App\Http\Controllers\StudentsController::class, 'enroll'])->name('studentenroll');    
    Route::any('/updatestudent', [App\Http\Controllers\StudentsController::class, 'update'])->name('studentudpate');    

});

// about student but can be accessed by all
Route::get('/studentprofile/{id?}/', [App\Http\Controllers\StudentsController::class, 'index'])->name('studentProfile')->middleware(['verified', 'member']);

// FACULTY protected routes 
Route::middleware([App\Http\Middleware\ProtectFacultyRoutesMiddleware::class])->group(function () {
    Route::get('/myclasses', [App\Http\Controllers\FacultiesController::class, 'getClasses'])->name('facultyClasses');

    Route::get('/myclass/{id}/', [App\Http\Controllers\FacultiesController::class, 'getClass'])->name('facultyClass');
    Route::get('/myclass/{id}/export', [App\Http\Controllers\FacultiesController::class, 'exportClass'])->name('exportClass');

    Route::get('/sortclass/{classid}/{facultyid}/{sortby}', [App\Http\Controllers\StudentClassesController::class, 'sortStudents'])->name('sortStudents');
    Route::any('/faculty/updaterating/', [App\Http\Controllers\SubjectsTakenController::class, 'updateRating'])->name('updaterating');
    Route::any('/archiveclass', [App\Http\Controllers\StudentClassesController::class, 'archiveClass'])->name('archiveclass');
    Route::get('/facultydetails/{id?}', [App\Http\Controllers\FacultiesController::class, 'show'])->name('facultydetails');
    Route::get('/showfacultydetail/{id}/{detail}', [App\Http\Controllers\FacultiesController::class, 'showDetail'])->name('showdetail');
    Route::any('/updatefaculty', [App\Http\Controllers\FacultiesController::class, 'update'])->name('updatefaculty');        
    
});






