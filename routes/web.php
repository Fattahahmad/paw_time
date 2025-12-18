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

Route::name('user.')->group(function () {
    Route::view('/dashboard', 'pages.user.dashboard')->name('dashboard');
    Route::view('/chart', 'pages.user.chart')->name('chart');
    Route::view('/reminder', 'pages.user.reminder')->name('reminder');
    Route::view('/profile', 'pages.user.profile')->name('profile');
    Route::view('/health', 'pages.user.health')->name('health');
});
