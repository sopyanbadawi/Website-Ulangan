<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\GuruMapelController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\UjianAttemptController;
use App\Http\Controllers\SiswaUjianController;
use App\Http\Middleware\CheckUjianIp;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/cek-ip', function () {
    return request()->ip();
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// SUPER ADMIN
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // USER MANAGEMENT
    Route::prefix('admin/user')->name('admin.user.')->group(function () {

        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');

        Route::get('/template-siswa/{mode}', [UserController::class, 'downloadTemplateSiswa'])
            ->name('template-siswa');


        Route::post('/import-siswa', [UserController::class, 'importSiswa'])
            ->name('import-siswa');

        Route::post('/store', [UserController::class, 'store'])->name('store');

        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [UserController::class, 'update'])->name('update');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
    });

    // ROLE MANAGEMENT
    Route::prefix('admin/role')->name('admin.role.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [RoleController::class, 'update'])->name('update');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
    });

    // KELAS MANAGEMENT
    Route::prefix('admin/kelas')->name('admin.kelas.')->group(function () {
        Route::get('/', [KelasController::class, 'index'])->name('index');
        Route::get('/create', [KelasController::class, 'create'])->name('create');
        Route::post('/store', [KelasController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [KelasController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [KelasController::class, 'update'])->name('update');
        Route::get('/{id}', [KelasController::class, 'show'])->name('show');
    });

    // TAHUN AJARAN MANAGEMENT
    Route::prefix('admin/tahun')->name('admin.tahun.')->group(function () {
        Route::get('/', [TahunAjaranController::class, 'index'])->name('index');
        Route::get('/create', [TahunAjaranController::class, 'create'])->name('create');
        Route::post('/store', [TahunAjaranController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TahunAjaranController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [TahunAjaranController::class, 'update'])->name('update');
        Route::get('/{id}', [TahunAjaranController::class, 'show'])->name('show');
    });

    // MATAPELAJARAN MANAGEMENT
    Route::prefix('admin/mapel')->name('admin.mapel.')->group(function () {
        Route::get('/', [MapelController::class, 'index'])->name('index');
        Route::get('/create', [MapelController::class, 'create'])->name('create');
        Route::post('/store', [MapelController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MapelController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [MapelController::class, 'update'])->name('update');
        Route::get('/{id}', [MapelController::class, 'show'])->name('show');
    });

    // GURU MAPEL MANAGEMENT
    Route::prefix('admin/guru-mapel')->name('admin.guru_mapel.')->group(function () {
        Route::get('/', [GuruMapelController::class, 'index'])->name('index');
        Route::get('/create', [GuruMapelController::class, 'create'])->name('create');
        Route::post('/store', [GuruMapelController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [GuruMapelController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [GuruMapelController::class, 'update'])->name('update');
        Route::get('/{id}', [GuruMapelController::class, 'show'])->name('show');
    });

    // UJIAN MANAGEMENT
    Route::prefix('admin/ujian')->name('admin.ujian.')->group(function () {

        // =====================
        // LIST & CREATE
        // =====================
        Route::get('/', [UjianController::class, 'index'])->name('index');
        Route::get('/create', [UjianController::class, 'create'])->name('create');
        Route::post('/store', [UjianController::class, 'store'])->name('store');

        // =====================
        // HASIL UJIAN (WAJIB DI ATAS!)
        // =====================
        Route::get('/{ujian}/hasil', [UjianAttemptController::class, 'index'])
            ->name('hasil');

        Route::get('/{ujian}/hasil/{kelas}', [UjianAttemptController::class, 'detail'])
            ->name('hasil-detail');

        // =====================
        // MONITORING
        // =====================
        Route::get('/monitoring', [UjianController::class, 'monitoring'])
            ->name('monitoring');
        Route::get('/monitoring/{ujian}', [UjianController::class, 'monitoringDetail'])
            ->name('monitoring-detail');
        Route::get('/monitoring/{ujian}/kelas/{kelas}', [UjianController::class, 'monitoringKelas'])
            ->name('monitoring-kelas');
        Route::post('/monitoring/{ujian}/kelas/{kelas}/attempt/{attempt}/unlock', [UjianController::class, 'unlockAttempt'])
            ->name('monitoring-unlock');
        Route::get('/monitoring/{ujian}/kelas/{kelas}/attempt/{attempt}', [UjianController::class, 'monitoringActivity'])
            ->name('monitoring-activity');

        // =====================
        // FILTER UJIAN
        // =====================
        Route::get('/all-aktif', [UjianController::class, 'allAktif'])->name('all_aktif');
        Route::get('/all-draft', [UjianController::class, 'allDraft'])->name('all_draft');
        Route::get('/all-selesai', [UjianController::class, 'allSelesai'])->name('all_selesai');

        // =====================
        // EDIT & DELETE (PALING BAWAH!)
        // =====================
        Route::get('/{id}/edit', [UjianController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [UjianController::class, 'update'])->name('update');
        Route::put('/{id}/activate', [UjianController::class, 'activate'])->name('activate');
        Route::delete('/soal/{soal}', [UjianController::class, 'destroySoal'])->name('soal.destroy');
        Route::delete('/{id}', [UjianController::class, 'destroy'])->name('destroy');

        // Export
        Route::get(
            '/{ujian}/export-excel',
            [UjianAttemptController::class, 'exportExcel']
        )->name('export_excel');
    });
});

// GURU
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru/dashboard', [DashboardController::class, 'guru'])->name('guru.dashboard');
    Route::prefix('guru/ujian')->name('guru.ujian.')->group(function () {
        Route::get('/', [UjianController::class, 'index'])->name('index');
        Route::get('/create', [UjianController::class, 'create'])->name('create');
        Route::post('/store', [UjianController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UjianController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [UjianController::class, 'update'])->name('update');
        Route::put('/{id}/activate', [UjianController::class, 'activate'])->name('activate');
        Route::delete('/soal/{soal}', [UjianController::class, 'destroySoal'])->name('soal.destroy');
        Route::get('/all-aktif', [UjianController::class, 'allAktif'])->name('all_aktif');
        Route::get('/all-draft', [UjianController::class, 'allDraft'])->name('all_draft');
        Route::get('/all-selesai', [UjianController::class, 'allSelesai'])->name('all_selesai');
        Route::delete('/{id}',[UjianController::class, 'destroy'])->name('destroy');

        Route::get('/monitoring', [UjianController::class, 'monitoring'])
            ->name('monitoring');
        Route::get('/monitoring/{ujian}', [UjianController::class, 'monitoringDetail'])
            ->name('monitoring-detail');
        Route::get('/monitoring/{ujian}/kelas/{kelas}', [UjianController::class, 'monitoringKelas'])
            ->name('monitoring-kelas');
        Route::post('/monitoring/{ujian}/kelas/{kelas}/attempt/{attempt}/unlock', [UjianController::class, 'unlockAttempt'])
            ->name('monitoring-unlock');
        Route::get('/monitoring/{ujian}/kelas/{kelas}/attempt/{attempt}', [UjianController::class, 'monitoringActivity'])
            ->name('monitoring-activity');

    });

Route::get('/guru/rekap', [KelasController::class, 'rekap'])->name('guru.rekap');
});

// SISWA
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        // Dashboard
        Route::get('/siswa/dashboard', [DashboardController::class, 'siswa'])->name('dashboard');

        // =======================
        // UJIAN SISWA
        // =======================
        Route::get('/ujian', [SiswaUjianController::class, 'index'])
            ->name('ujian.index');

        Route::get('/ujian/{id}/start', [SiswaUjianController::class, 'start'])
            ->middleware(CheckUjianIp::class)
            ->name('ujian.start');

        Route::get('/ujian/attempt/{attempt}', [SiswaUjianController::class, 'kerjakan'])
            ->name('ujian.kerjakan');

        Route::post('/ujian/attempt/{attempt}/jawab', [SiswaUjianController::class, 'jawab'])
            ->name('ujian.jawab');

        Route::post('/ujian/attempt/{attempt}/submit', [SiswaUjianController::class, 'submit'])
            ->name('ujian.submit');

        Route::post('/ujian/attempt/{attempt}/lock', [SiswaUjianController::class, 'lock'])
            ->name('ujian.lock');


        Route::get('/siswa/riwayat', [SiswaUjianController::class, 'riwayat'])->name('riwayat-ujian.riwayat');
    });


require __DIR__ . '/auth.php';
