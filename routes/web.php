<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $emailAccounts = \Illuminate\Support\Facades\Auth::user()->emailAccounts;
    return view('dashboard', compact('emailAccounts'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // OAuth Routes
    Route::get('/oauth/google/redirect', [\App\Http\Controllers\OAuthController::class, 'redirect'])->name('oauth.google.redirect');
    Route::get('/oauth/google/callback', [\App\Http\Controllers\OAuthController::class, 'callback'])->name('oauth.google.callback');
});

require __DIR__.'/auth.php';
