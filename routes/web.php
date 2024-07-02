<?php

use App\Http\Controllers\JobDesignationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;

//Jobs Routes
Route::middleware('auth')->group(function () {
    Route::get('/jobs/home', [JobsController::class, 'home'])->name('jobs.home');
    Route::get('/jobs', [JobsController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [JobsController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobsController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}/edit', [JobsController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [JobsController::class, 'update'])->name('jobs.update');
    Route::get('/jobs/{job}', [JobsController::class, 'show'])->name('jobs.show');
    Route::delete('/jobs/{job}', [JobsController::class, 'destroy'])->name('jobs.destroy');
    Route::put('/jobs/{job}/status', [JobsController::class, 'status'])->name('jobs.status');
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
    //Status of Job Route
    Route::put('/jobs/{job}/status', [JobsController::class, 'status'])->name('jobs.status');
});


// Auth Routes
Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
Route::post('/register', [RegisterUserController::class, 'store'])->name('register');
Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store'])->name('login');

//Job Designations Routes
Route::middleware('auth')->group(function () {
    Route::get('/designations', [JobDesignationController::class, 'index'])->name('designations.index');
    Route::get('/designations/create', [JobDesignationController::class, 'create'])->name('designations.create');
    Route::post('/designations', [JobDesignationController::class, 'store'])->name('designations.store');
    Route::get('/designations/{jobDesignation}', [JobDesignationController::class, 'show'])->name('designations.show');
    Route::get('/designations/{jobDesignation}/edit', [JobDesignationController::class, 'edit'])->name('designations.edit');
    Route::put('/designations/{jobDesignation}', [JobDesignationController::class, 'update'])->name('designations.update');
    Route::delete('/designations/{jobDesignation}', [JobDesignationController::class, 'destroy'])->name('designations.destroy');
});

//Job Students Routes
Route::middleware('auth')->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
});

//Job Subjects Routes
Route::middleware('auth')->group(function () {
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    // routes AssignSubject
    Route::post('/students/assign-subjects', [StudentController::class, 'assignSubjects'])->name('assign.subjects');
});

