<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WisataController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [WisataController::class, 'halamanLogin'])->name('login');
Route::post('/login-proses', [WisataController::class, 'prosesLogin']);
Route::get('/register', [WisataController::class, 'halamanRegister']);
Route::post('/register-proses', [WisataController::class, 'prosesRegister']);
Route::get('/logout', [WisataController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [WisataController::class, 'dashboard']);
    Route::get('/users', [WisataController::class, 'tampilUsers']);
    Route::get('/paket-wisata', [WisataController::class, 'tampilPaket']);
    Route::post('/pesan-paket', [WisataController::class, 'prosesPesan']);
    Route::get('/kendaraan', [WisataController::class, 'tampilKendaraan']);
    Route::get('/jadwal-keberangkatan', [WisataController::class, 'tampilJadwal']);
    Route::get('/custom-trip', [WisataController::class, 'tampilCustomTrip']);
    Route::get('/pemesanan', [WisataController::class, 'tampilPemesanan']);
    Route::get('/peserta', [WisataController::class, 'tampilPeserta']);
    Route::get('/pembayaran', [WisataController::class, 'tampilPembayaran']);
    Route::post('/proses-bayar', [WisataController::class, 'prosesBayar']);
    Route::get('/verifikasi-pembayaran/{id}', [WisataController::class, 'verifikasiPembayaran']);
    Route::get('/tiket', [WisataController::class, 'tampilTiket']);
    Route::get('/pengembalian', [WisataController::class, 'tampilPengembalian']);
    Route::get('/laporan', [WisataController::class, 'tampilLaporan']);

    Route::get('/profil', function () {
        return view('profil');
    });

    Route::get('/ubah-password', function () {
        return view('ubah_password');
    });
});