<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OrphanController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\SponsorshipController;
use App\Http\Controllers\SponsorAuthController;
use App\Http\Controllers\OrphanAuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================== Public ==================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ================== Admin Authentication ==================
Route::prefix('admin')->group(function () {

    Route::get('login', function () {
        return view('admin.login');
    })->name('admin.login');

    Route::post('login', function (Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'بيانات الدخول غير صحيحة.');
    })->name('admin.login.submit');

    Route::post('logout', function () {
        Auth::guard('admin')->logout();
        return redirect()->route('home');
    })->name('admin.logout');
});

Route::get('/statistics', [OrphanController::class, 'statistics'])->name('statistics');

// ================== Admin Protected Routes ==================
Route::middleware(['auth:admin', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::post('toggle-status/{type}/{id}', [AdminController::class, 'toggleStatus'])->name('admin.toggleStatus');
    Route::post('toggle-verify/{type}/{id}', [AdminController::class, 'toggleVerify'])->name('admin.toggleVerify');
    Route::post('verify/{type}/{id}', [AdminController::class, 'verifyData'])->name('admin.verifyData');

    // إضافة Route لعرض جدول الكفالات لكل يتيم للمدير
    Route::get('orphans/{id}/sponsorships', [AdminController::class, 'showOrphanSponsorships'])->name('admin.orphan.sponsorships');
    // إعادة تعيين كلمة مرور اليتيم بواسطة المدير
Route::post('orphans/{id}/force-reset-password', [AdminController::class, 'forceResetOrphanPassword'])
    ->name('admin.orphans.forceResetOrphanPassword');


// إعادة تعيين كلمة مرور الكفيل بواسطة المدير
Route::post('sponsors/{id}/force-reset-password', [AdminController::class, 'forceResetSponsorPassword'])
    ->name('admin.sponsors.forceResetSponsorPassword');


    // حذف مستخدم (يتيم أو كفيل)
Route::delete('delete/{type}/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');


});

// ================== Orphans Authentication ==================
Route::prefix('orphans')->group(function () {
    Route::get('register', [OrphanAuthController::class, 'showRegisterForm'])->name('orphans.register');
    Route::post('register', [OrphanAuthController::class, 'register'])->name('orphans.register.submit');

    Route::get('login', [OrphanAuthController::class, 'showLoginForm'])->name('orphans.login');
    Route::post('login', [OrphanAuthController::class, 'login'])->name('orphans.login.submit');

    Route::middleware('auth:orphan')->group(function () {
        Route::get('dashboard', [OrphanAuthController::class, 'dashboard'])->name('orphans.dashboard');
        Route::get('list', [OrphanAuthController::class, 'listOrphans'])->name('orphans.list');
        Route::post('logout', [OrphanAuthController::class, 'logout'])->name('orphans.logout');

        // إضافة Route لعرض جدول الكفالات للطفل نفسه
        Route::get('{id}/sponsorships', [OrphanController::class, 'showSponsorships'])->name('orphans.sponsorships');
    });
});

// ================== Orphans CRUD ==================
Route::resource('orphans', OrphanController::class)->except(['index', 'create', 'store']);
Route::get('/orphans', [OrphanController::class, 'index'])->name('orphans.index');
Route::get('/orphans/create', [OrphanController::class, 'create'])->name('orphans.create');
Route::post('/orphans', [OrphanController::class, 'store'])->name('orphans.store');
Route::post('/orphans/{orphan}/sponsor', [OrphanController::class, 'sponsor'])->name('orphans.sponsor');

// ================== Sponsors Authentication & Routes ==================
Route::prefix('sponsor')->group(function () {

    Route::get('register', [SponsorController::class,'showRegister'])->name('sponsor.register');
    Route::post('register', [SponsorController::class,'register'])->name('sponsor.register.post');

    Route::get('login', [SponsorController::class,'showLogin'])->name('sponsor.login');
    Route::post('login', [SponsorController::class,'login'])->name('sponsor.login.post');

    Route::post('logout', [SponsorAuthController::class, 'logout'])->name('sponsor.logout');

    Route::middleware('auth:sponsor')->group(function() {
        Route::get('dashboard', [SponsorController::class,'dashboard'])->name('sponsor.dashboard');
        Route::get('edit', [SponsorController::class,'edit'])->name('sponsor.edit');
        Route::put('update', [SponsorController::class,'update'])->name('sponsor.update');

        Route::get('orphans', [SponsorController::class, 'index'])->name('sponsor.orphans.index');
        Route::get('orphan/{id}', [SponsorController::class,'showOrphan'])->name('sponsor.orphan.show');

        Route::get('orphan/{id}/sponsorship/create', [SponsorController::class,'createSponsorship'])->name('sponsor.sponsorship.create');
        Route::post('orphan/{id}/sponsorship/store', [SponsorController::class,'storeSponsorship'])->name('sponsor.sponsorship.store');

        Route::get('sponsorships', [SponsorController::class, 'sponsorships'])->name('sponsor.sponsorships');
        Route::get('sponsorships/{id}', [SponsorController::class, 'showSponsorship'])->name('sponsor.sponsorship.show');
    });
});

// ================== Sponsorships Resource ==================
Route::resource('sponsorships', SponsorshipController::class);
