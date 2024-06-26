<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;

Route::get('/jobs/home', [JobsController::class, 'home'])->name('home');

Route::get('/jobs', [JobsController::class, 'index'])->name('index');

Route::get('/jobs/create', [JobsController::class, 'create'])->name('create');
Route::post('/jobs', [JobsController::class, 'store'])->name('store');

Route::get('/jobs/{job}/edit', [JobsController::class, 'edit'])->name('edit');
Route::put('/jobs/{job}', [JobsController::class, 'update'])->name('update');

Route::get('/jobs/{job}', [JobsController::class, 'show'])->name('show');

Route::delete('/jobs/{job}', [JobsController::class, 'destroy'])->name('destroy');

//Auth Routes
Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
Route::post('/register', [RegisterUserController::class, 'store'])->name('register');

//Login route
Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store'])->name('login');

//Logout Route
Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');

