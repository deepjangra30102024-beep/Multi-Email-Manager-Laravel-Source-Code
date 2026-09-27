<?php

use App\Http\Controllers\EmailAccountController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $emailAccounts = Auth::user()->emailAccounts;

    return view('dashboard', compact('emailAccounts'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // OAuth Routes
    Route::get('/oauth/google/redirect', [OAuthController::class, 'redirect'])->name('oauth.google.redirect');
    Route::get('/oauth/google/callback', [OAuthController::class, 'callback'])->name('oauth.google.callback');

    // Email Accounts
    Route::delete('/email-accounts/{emailAccount}', [EmailAccountController::class, 'destroy'])->name('email-accounts.destroy');

    // Inbox
    Route::get('/inbox/{emailAccount}', [InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{emailAccount}/compose', [InboxController::class, 'compose'])->name('inbox.compose');
    Route::post('/inbox/{emailAccount}/send', [InboxController::class, 'send'])->name('inbox.send');
    Route::get('/inbox/{emailAccount}/{messageId}', [InboxController::class, 'show'])->name('inbox.show');

    // Templates
    Route::resource('templates', TemplateController::class)->except(['show']);
});

require __DIR__.'/auth.php';
