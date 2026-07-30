<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProfileController as PublicProfileController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\TeacherController;
use App\Http\Controllers\Public\ExtracurricularController;
use App\Http\Controllers\Public\PpdbController;
use App\Http\Controllers\Public\DownloadController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\CalendarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\ExtracurricularController as AdminExtracurricularController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PpdbRegistrationController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\PpdbSettingController;
use App\Http\Controllers\Admin\DownloadController as AdminDownloadController;
use App\Http\Controllers\Admin\AcademicCalendarController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\FacilityController;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [PublicProfileController::class, 'index'])->name('profile');
Route::get('/guru-staf', [TeacherController::class, 'index'])->name('teachers');
Route::get('/ekstrakurikuler', [ExtracurricularController::class, 'index'])->name('extracurriculars');
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
// Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery');
Route::get('/ppdb', [PpdbController::class, 'index'])->name('ppdb');
Route::post('/ppdb', [PpdbController::class, 'store'])->name('ppdb.store');
Route::get('/ppdb/cek-status', [PpdbController::class, 'showCheckStatus'])->name('ppdb.check');
Route::post('/ppdb/cek-status', [PpdbController::class, 'checkStatus'])->name('ppdb.check.submit');
Route::get('/ppdb/bukti/{no_pendaftaran}', [PpdbController::class, 'bukti'])->name('ppdb.bukti');
Route::get('/unduhan', [DownloadController::class, 'index'])->name('downloads');
Route::get('/kalender', [CalendarController::class, 'index'])->name('calendar');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

// ============================================
// AUTH PROFILE ROUTES (dari Breeze)
// ============================================
Route::middleware('auth')->group(function () {
    // Alias 'dashboard' -> panel admin (dipakai oleh redirect bawaan Breeze setelah login/registrasi/verifikasi).
    Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// ADMIN ROUTES
// ============================================
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Operator & Super Admin routes
    Route::middleware('role:super-admin,operator')->group(function () {
        Route::resource('berita', AdminNewsController::class);
        // Route::resource('galeri', AdminGalleryController::class)->only(['index', 'store', 'destroy']);
        Route::resource('guru', AdminTeacherController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('ekstrakurikuler', AdminExtracurricularController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('unduhan', AdminDownloadController::class)->only(['index', 'store', 'destroy']);
        Route::resource('kalender', AcademicCalendarController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('ppdb/export', [PpdbRegistrationController::class, 'export'])->name('ppdb.export');
        Route::resource('ppdb', PpdbRegistrationController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('ppdb-setting', PpdbSettingController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('pesan', ContactMessageController::class)->only(['index', 'show', 'destroy']);
        Route::patch('pesan/{pesan}/read', [ContactMessageController::class, 'markAsRead'])->name('pesan.read');
        Route::resource('siswa', \App\Http\Controllers\Admin\StudentController::class)->parameters(['siswa' => 'student'])->except(['show']);
        Route::resource('master-data', MasterDataController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('fasilitas', FacilityController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('halaman', [PageController::class, 'index'])->name('pages.index');
        Route::get('halaman/{slug}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('halaman/{slug}', [PageController::class, 'update'])->name('pages.update');
        Route::get('pengaturan', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('pengaturan', [SettingController::class, 'update'])->name('settings.update');
    });

    // Super Admin only routes
    Route::middleware('role:super-admin')->group(function () {
        Route::resource('pengguna', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});

require __DIR__.'/auth.php';
