<?php

use App\Http\Controllers\GuestController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;


Route::get('/locale/{locale}', [LocaleController::class, 'locale'])->name('locale');


Route::controller(GuestController::class)
    ->name('guest.')
    ->group(function() {
        Route::name('fr.')->group(function () {
            Route::get('/', 'index')->name('home');
            Route::get('/a-propos-de-nous', 'about')->name('about');
            Route::get('/la-carte', 'menu')->name('menu');
            Route::get('/restaurant', 'restaurant')->name('restaurant');
            Route::get('/hotel', 'hotel')->name('hotel');
            Route::get('/contact', 'contact')->name('contact');
        });

        Route::name('en.')->prefix('en')->group(function () {
            Route::get('/', 'index')->name('home');
            Route::get('/about-us', 'about')->name('about');
            Route::get('/menu', 'menu')->name('menu');
            Route::get('/restaurant', 'restaurant')->name('restaurant');
            Route::get('/hotel', 'hotel')->name('hotel');
            Route::get('/contact', 'contact')->name('contact');
        });
    });

