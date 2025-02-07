<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::view('/', 'dashboard')->name('dashboard');
    Route::view('/{id}', 'dashboard_project')->name('dashboard_project');
    Route::view('/{id}/{user_id}', 'dashboard_employee')->name('dashboard_employee');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
require __DIR__.'/worksnap.php';
