<?php

use App\Http\Controllers\JobDesignationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;

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
//Status of Job Route
Route::put('/jobs/{job}/status', [JobsController::class, 'status'])->name('jobs.status');


