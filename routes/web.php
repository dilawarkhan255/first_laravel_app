<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobDesignationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


    //Social Routes
    Route::get('login/{provider}', [SocialController::class, 'redirect'])->name('auth.redirect');
    Route::get('login/{provider}/callback', [SocialController::class, 'callBack'])->name('auth.callback');

    //Login\Resgistration Route
    Auth::routes(['verify' => true]);
    // Route::middleware(['throttle:global'])->group(function () {
    //     Auth::routes(['verify' => true]);
    // });


    //Home Routes
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/home/job/{slug}', [HomeController::class, 'job_details'])->name('job_details');
    Route::get('/home/view_job', [HomeController::class, 'view_job'])->name('view_job');
    Route::post('/load-more-jobs', [HomeController::class, 'loadmorejobs'])->name('loadmorejobs');
    Route::post('/send-emails', [HomeController::class, 'sendEmailToUsers'])->name('send.emails');


Route::middleware('auth')->group(function () {

    //Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    //Jobs Routes
    Route::get('/dashboard', [DashboardController::class, 'home'])->name('dashboard.home');
    Route::get('/jobs', [JobsController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [JobsController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobsController::class, 'store'])->name('jobs.store')->middleware(['throttle:global']);
    Route::get('/jobs/{job}/edit', [JobsController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [JobsController::class, 'update'])->name('jobs.update');
    Route::get('/jobs/{job}', [JobsController::class, 'show'])->name('jobs.show');
    Route::delete('/jobs/{job}', [JobsController::class, 'destroy'])->name('jobs.destroy');
    Route::put('/jobs/{job}/status', [JobsController::class, 'status'])->name('jobs.status');
    Route::get('/search', [JobsController::class, 'search'])->name('search');

    //import export & Pdf Routes
    Route::get('/export', [JobsController::class, 'export'])->name('jobs.export')->middleware(['throttle:global']);
    Route::post('/import', [JobsController::class,'import'])->name('jobs.import');
    Route::get('pdf/generate-pdf', [PDFController::class, 'generatePDF'])->name('pdf.generatePDF');

    //Job Designations Routes
    Route::get('/designations', [JobDesignationController::class, 'index'])->name('designations.index');
    Route::get('/designations/create', [JobDesignationController::class, 'create'])->name('designations.create');
    Route::post('/designations', [JobDesignationController::class, 'store'])->name('designations.store');
    Route::get('/designations/{jobDesignation}', [JobDesignationController::class, 'show'])->name('designations.show');
    Route::get('/designations/{jobDesignation}/edit', [JobDesignationController::class, 'edit'])->name('designations.edit');
    Route::put('/designations/{jobDesignation}', [JobDesignationController::class, 'update'])->name('designations.update');
    Route::delete('/designations/{jobDesignation}', [JobDesignationController::class, 'destroy'])->name('designations.destroy');

    //Job Students Routes
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    //Job Subjects Routes
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    // routes Assign/UnAssign Subjects
    Route::post('/students/assign-subjects', [StudentController::class, 'assignSubjects'])->name('assign.subjects')->middleware(['throttle:global']);
    Route::post('students/unassign-subjects', [StudentController::class, 'unassignSubjects'])->name('unassign.subjects');
    Route::get('/students/{id}/available-subjects', [StudentController::class, 'getAvailableSubjects'])->name('get.available.subjects');
    //Routes Assign/UnAssign subjects
    Route::post('/subjects/assign-students', [SubjectController::class, 'assignStudents'])->name('assign.students');
    Route::post('subjects/unassign-students', [SubjectController::class, 'unassignStudents'])->name('unassign.students');
    Route::get('/subjects/{id}/available-students', [SubjectController::class, 'getAvailableStudents'])->name('get.available.students');

    //Bulk Delete Students
    Route::post('students/bulk-delete', [StudentController::class, 'bulkDelete'])->name('students.bulkDelete');
    //Bulk Delete Subjects
    Route::post('subjects/bulk-delete', [SubjectController::class, 'bulkDelete'])->name('subjects.bulkDelete');

    //Roles and Permissions
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    //applicants routes
    Route::get('/applicants', [ApplicantController::class, 'index'])->name('applicants.index');
    Route::post('/job_listings/{job}/apply', [ApplicantController::class, 'store'])->name('applicants.store');
    // Route::delete('/applicants/{applicant}', [ApplicantController::class, 'destroy'])->name('applicants.destroy');

    //Attachments Route
    Route::post('/upload-image', [UserController::class, 'uploadImage'])->name('user.upload_image');

    //Favourite Route
    Route::post('/favourite', [FavouriteController::class, 'toggleFavourite'])->name('favourite.toggle');

});


// Auth Routes
// Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
// Route::post('/register', [RegisterUserController::class, 'store'])->name('register');
// Route::get('/login', [SessionController::class, 'create'])->name('login');
// Route::post('/login', [SessionController::class, 'store'])->name('login');
//Users Profile Image
// Route::post('upload-single', [AttachmentController::class, 'uploadSingle'])->name('upload.single');
// Route::post('/upload-multiple', [AttachmentController::class, 'uploadMultiple'])->name('upload.multiple');





