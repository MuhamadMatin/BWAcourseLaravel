<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseVideoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\SubscribeTransactionController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');

Route::get('dashboard', [DashboardController::class, 'index'])
    // ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/details/{course:slug}', [FrontController::class, 'details'])->name('front.details');

Route::get('/category/{category:slug}', [FrontController::class, 'category'])->name('front.category');


Route::get('/pricing', [FrontController::class, 'pricing'])->name('front.pricing');

Route::middleware('auth')->group(function () {
    Route::view('/profile', 'profile')
        ->name('profile');

    // transaction checkout
    Route::get('/checkout', [FrontController::class, 'checkout'])
        ->middleware('role:student')
        ->name('front.checkout');
    Route::post('/checkout/store', [FrontController::class, 'checkoutStore'])
        ->middleware('role:student')
        ->name('front.checkout.store');

    // Student
    Route::get('/learning/{course}/{courseVideoId}', [FrontController::class, 'learning'])
        ->middleware('role:owner|admin|student|teacher')
        ->name('front.learning');

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        // penamaan resourse jangan ditambahkan bintang contoh admin.*
        Route::resource('categories', CategoryController::class)
            ->middleware('role:owner|admin');
        Route::resource('teachers', TeacherController::class)
            ->middleware('role:owner|admin');
        Route::resource('courses', CourseController::class)
            ->middleware('role:owner|admin|teacher');
        Route::resource('transaction', SubscribeTransactionController::class)
            ->middleware('role:owner|admin');

        Route::resource('course_videos', CourseVideoController::class)
            ->middleware('role:owner|admin|teacher');

        Route::get('add/video/{course:id}', [CourseVideoController::class, 'create'])
            ->middleware('role:owner|admin|teacher')
            ->name('course.add_video');
        Route::post('add/video/save/{course:id}', [CourseVideoController::class, 'store'])
            ->middleware('role:owner|admin|teacher')
            ->name('course.add_video.save');
    });
});


require __DIR__ . '/auth.php';
