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

    // Email Accounts
    Route::delete('/email-accounts/{emailAccount}', [\App\Http\Controllers\EmailAccountController::class, 'destroy'])->name('email-accounts.destroy');

    // Inbox
    Route::get('/inbox/{emailAccount}', [\App\Http\Controllers\InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{emailAccount}/{messageId}', [\App\Http\Controllers\InboxController::class, 'show'])->name('inbox.show');

    // Templates
    Route::resource('templates', \App\Http\Controllers\TemplateController::class)->except(['show']);
});

require __DIR__.'/auth.php';
