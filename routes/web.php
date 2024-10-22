<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

Route::get('/locale/{locale}', [LocaleController::class, 'locale'])->name('locale');

Route::controller(AuthController::class)->name('auth.')->group(function() {
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(PageController::class)
    ->name('page.')
    ->prefix('page')
    ->group(function() {
        Route::put('/{page}', 'update')->name('update');
    });




Route::controller(AdminController::class)
    ->middleware(AdminMiddleware::class)
    ->name('admin.')
    ->prefix('admin')
    ->group(function() {
        Route::get('/home', 'home')->name('home');
        Route::get('/menu', 'menu')->name('menu');
        Route::get('/restaurant', 'restaurant')->name('restaurant');
        Route::get('/hotel', 'hotel')->name('hotel');
        Route::get('/contact', 'contact')->name('contact');

        Route::controller(MenuController::class)
            ->name('menu.')
            ->prefix('menu')
            ->group(function() {
                Route::delete('/{section}', 'destroy')->name('delete');
                Route::delete('/', 'remove')->name('remove');
                Route::put('/{menu}', 'update')->name('update');
                Route::post('/', 'create')->name('create');
            });
    });

Route::controller(GalleryController::class)
    ->name('gallery.')
    ->prefix('gallery')
    ->group(function() {
        Route::post('/{gallery}', 'store')->name('store');
        Route::delete('/', 'destroy')->name('delete');
    });

Route::controller(GuestController::class)
    ->name('guest.')
    ->group(function() {
        Route::name('fr.')->group(function () {
            Route::get('/', 'index')->name('home');
            Route::get('/la-carte', 'menu')->name('menu');
            Route::get('/restaurant', 'restaurant')->name('restaurant');
            Route::get('/hotel', 'hotel')->name('hotel');
            Route::get('/contact', 'contact')->name('contact');
        });

        Route::name('en.')->prefix('en')->group(function () {
            Route::get('/', 'index')->name('home');
            Route::get('/menu', 'menu')->name('menu');
            Route::get('/restaurant', 'restaurant')->name('restaurant');
            Route::get('/hotel', 'hotel')->name('hotel');
            Route::get('/contact', 'contact')->name('contact');
        });
    });

Route::controller(ContactController::class)
    ->name('contact.')
    ->group(function() {
        Route::post('/', 'store')->name('store');
    });

