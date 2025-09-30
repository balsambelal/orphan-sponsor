<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrphanController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\SponsorshipController;
use App\Http\Controllers\SponsorAuthController;
use App\Http\Controllers\OrphanAuthController;

// ================== Public ==================
Route::get('/', function () {
    return view('welcome');
})->name('home');




// ================== Orphans Authentication ==================
// صفحة تسجيل الحساب الجديد لليتيم
Route::get('/orphans/register', [OrphanAuthController::class, 'showRegisterForm'])->name('orphans.register');
Route::post('/orphans/register', [OrphanAuthController::class, 'register'])->name('orphans.register.submit');

// صفحة تسجيل دخول اليتيم
Route::get('/orphans/login', [OrphanAuthController::class, 'showLoginForm'])->name('orphans.login');
Route::post('/orphans/login', [OrphanAuthController::class, 'login'])->name('orphans.login.submit');

// ================== Orphans CRUD ==================
// عرض قائمة الأيتام
Route::get('/orphans', [OrphanController::class, 'index'])->name('orphans.index');

// إضافة يتيم جديد (للمشرف)
Route::get('/orphans/create', [OrphanController::class, 'create'])->name('orphans.create');
Route::post('/orphans', [OrphanController::class, 'store'])->name('orphans.store');

// ================== Orphan Protected Routes (After Login) ==================
Route::middleware('auth:orphan')->group(function () {
    Route::get('/orphans/dashboard', [OrphanAuthController::class, 'dashboard'])->name('orphans.dashboard');
    Route::get('/orphans/list', [OrphanAuthController::class, 'listOrphans'])->name('orphans.list');
    Route::post('/orphans/logout', [OrphanAuthController::class, 'logout'])->name('orphans.logout');
});

// ================== Orphans Dynamic Routes ==================
// يجب أن تكون هذه آخر Routes الخاصة باليتيم لأنها تحتوي على {orphan}
Route::get('/orphans/{orphan}', [OrphanController::class, 'show'])->name('orphans.show');
Route::get('/orphans/{orphan}/edit', [OrphanController::class, 'edit'])->name('orphans.edit');
Route::put('/orphans/{orphan}', [OrphanController::class, 'update'])->name('orphans.update');
Route::delete('/orphans/{orphan}', [OrphanController::class, 'destroy'])->name('orphans.destroy');
Route::post('/orphans/{orphan}/sponsor', [OrphanController::class, 'sponsor'])->name('orphans.sponsor');


// ================== Sponsors ==================
Route::prefix('sponsor')->group(function () {

    // تسجيل الكفيل
    Route::get('register', [SponsorController::class,'showRegister'])->name('sponsor.register');
    Route::post('register', [SponsorController::class,'register'])->name('sponsor.register.post');

    // تسجيل دخول الكفيل
    Route::get('login', [SponsorController::class,'showLogin'])->name('sponsor.login');
    Route::post('login', [SponsorController::class,'login'])->name('sponsor.login.post');
    Route::post('logout', [SponsorAuthController::class, 'logout'])->name('sponsor.logout');

    Route::middleware('auth:sponsor')->group(function(){
        Route::get('dashboard', [SponsorController::class,'dashboard'])->name('sponsor.dashboard');
        Route::get('edit', [SponsorController::class,'edit'])->name('sponsor.edit');
        Route::put('update', [SponsorController::class,'update'])->name('sponsor.update');

        // عرض صفحة تفاصيل اليتيم للكفيل
        Route::get('orphan/{id}', [SponsorController::class,'showOrphan'])->name('sponsor.orphan.show');

        // إنشاء الكفالة
        Route::get('orphan/{id}/sponsorship/create', [SponsorController::class,'createSponsorship'])->name('sponsor.sponsorship.create');
        Route::post('orphan/{id}/sponsorship/store', [SponsorController::class,'storeSponsorship'])->name('sponsor.sponsorship.store');

        // عرض الكفالات
        Route::get('sponsorships', [SponsorController::class, 'sponsorships'])->name('sponsor.sponsorships');
        Route::get('sponsorships/{id}', [SponsorController::class, 'showSponsorship'])->name('sponsor.sponsorship.show');
    });
});

// ================== Sponsorships Resource ==================
Route::resource('sponsorships', SponsorshipController::class);




