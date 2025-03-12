<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;


//Welcome ventana princiapl de laravel
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Registro de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Categorias
Route::middleware('auth')->group(function () {
    Route::resource('categorias', CategoryController::class);
});
require __DIR__.'/auth.php';
