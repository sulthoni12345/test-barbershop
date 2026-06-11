<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Kasir & Admin — Transaksi + Cetak Struk
Route::middleware(['auth', 'role:kasir,admin'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}/struk', [TransactionController::class, 'struk'])->name('transactions.struk');
});

// Admin — Kelola Layanan
require __DIR__.'/auth.php';
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('services', ServiceController::class);
});
