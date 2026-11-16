
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TenantRoomController;
use App\Http\Controllers\PaymentController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Login dan Register
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.process');
});

// Semua pengguna yang sudah login
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Khusus Admin
    Route::middleware('admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [AuthController::class, 'dashboard'])
                ->name('dashboard');

            Route::resource('rooms', RoomController::class);

            Route::patch('/rooms/{room}/status', [RoomController::class, 'toggleStatus'])
                ->name('rooms.status');

            Route::get('/reports', [ReportController::class, 'index'])
                ->name('reports.index');

            Route::get('/reports/pdf', [ReportController::class, 'pdf'])
                ->name('reports.pdf');

            // Pembayaran masuk
            Route::get('/payments', [PaymentController::class, 'adminIndex'])
                ->name('payments.index');

            Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verify'])
                ->name('payments.verify');
        });

    // Khusus Penyewa
    Route::middleware('tenant')
        ->prefix('tenant')
        ->name('tenant.')
        ->group(function () {

            Route::get('/dashboard', [AuthController::class, 'dashboard'])
                ->name('dashboard');

            // Daftar dan detail kamar
            Route::get('/rooms', [TenantRoomController::class, 'index'])
                ->name('rooms.index');

            Route::get('/rooms/{id}', [TenantRoomController::class, 'show'])
                ->name('rooms.show');

            // Formulir booking kamar
            Route::get('/rooms/{roomId}/booking', [BookingController::class, 'create'])
                ->name('bookings.create');

            Route::post('/rooms/{roomId}/booking', [BookingController::class, 'store'])
                ->name('bookings.store');

            // Booking milik penyewa
            Route::get('/bookings', [BookingController::class, 'index'])
                ->name('bookings.index');

            Route::get('/bookings/{id}', [BookingController::class, 'show'])
                ->name('bookings.show');

            // Pembayaran penyewa
            Route::get('/bookings/{bookingId}/payment', [PaymentController::class, 'create'])
                ->name('payments.create');

            Route::post('/bookings/{bookingId}/payment', [PaymentController::class, 'store'])
                ->name('payments.store');

            Route::get('/payments', [PaymentController::class, 'tenantIndex'])
                ->name('payments.index');
        });
});
