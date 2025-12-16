<?php

use Illuminate\Support\Facades\Route;

Route::name('pages.')->group(function () {
    Route::view('/', 'pages.home')->name('home');
    Route::view('/about', 'pages.about')->name('about');
    Route::view('/contact', 'pages.contact')->name('contact');
});

Route::name('auth.')->group(function () {
    Route::view('/login', 'pages.auth')->name('login');
});

Route::view('/dashboard', 'pages.dashboard')->name('dashboard');
