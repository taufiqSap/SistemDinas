<?php

use App\Http\Controllers\Booking as BookingController;
use App\Http\Controllers\Fasilitas as FasilitasController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FasilitasController as AdminFasilitasController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/booking/show/{date}', [BookingController::class, 'show'])->name('booking.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'active', 'role:admin'])
    ->name('dashboard');

Route::prefix('admin')->middleware(['auth', 'active', 'role:admin','no-cache'])->name('admin.')->group(function () {
    Route::get('/fasilitas', [AdminFasilitasController::class, 'index'])->name('fasilitas.index');
    Route::get('/fasilitas/create', [AdminFasilitasController::class, 'create'])->name('fasilitas.create');
    Route::post('/fasilitas', [AdminFasilitasController::class, 'store'])->name('fasilitas.store');
    Route::get('/fasilitas/{fasilitas}/edit', [AdminFasilitasController::class, 'edit'])->name('fasilitas.edit');
    Route::put('/fasilitas/{fasilitas}', [AdminFasilitasController::class, 'update'])->name('fasilitas.update');
    Route::delete('/fasilitas/{fasilitas}', [AdminFasilitasController::class, 'destroy'])->name('fasilitas.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::put('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
    

    Route::get('/kategori', [AdminKategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [AdminKategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [AdminKategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{kategori}/edit', [AdminKategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{kategori}', [AdminKategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [AdminKategoriController::class, 'destroy'])->name('kategori.destroy');

    Route::get('/bookings/create', [AdminBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [AdminBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
});

Route::middleware(['auth', 'verified', 'active', 'role:user','no-cache'])->group(function () {
    Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas.index');
    Route::get('/fasilitas/{id}', [FasilitasController::class, 'show'])->name('fasilitas.show');
    Route::get('/booking/history', [BookingController::class, 'history'])->name('booking.history');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::put('/booking/cancel/{id}', [BookingController::class, 'cancel'])->name('booking.cancel');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';