<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\CompraController;


use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;


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
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('compras', CompraController::class);
    Route::delete('/product-images/{id}', [ProductImageController::class, 'destroy'])->name('product-images.destroy');
    Route::get('/compras/{id}/pdf', function ($id) {
        $compra = Compra::with('supplier', 'detalles.product')->findOrFail($id);
    
        $pdf = PDF::loadView('compras.pdf', compact('compra'));
        return $pdf->stream("compra-{$id}.pdf");
    })->name('compras.pdf'); 
    Route::get('/generar-pdf', [CompraController::class, 'generarPDF']);   


});
require __DIR__.'/auth.php';
