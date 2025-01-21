<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Login_Controller;
use App\Http\Controllers\Signup_Controller;
use App\Http\Controllers\Home_Controller;
use App\Http\Controllers\Ticket_Controller;
use App\Http\Controllers\Device_Management_Controller;
use App\Http\Controllers\User_Management_Controller;
use App\Http\Controllers\Audit_logs_Controller;
use App\Http\Controllers\Report_Controller;


Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Auth::routes(); 

Route::middleware(['web'])->group(function() {
Route::get('/', [Login_Controller::class, 'showLogin'])->name('login');
Route::get('/login', [Login_Controller::class, 'showLogin']);
Route::middleware('ClearExpiredSession')->post('/login', [Login_Controller::class, 'authenticate'])->name('login.authenticate');

Route::get('/signup', [Login_Controller::class, 'showSignup'])->name('signup');
Route::post('/signup', [Signup_Controller::class, 'storeSignup'])->name('signup.store');

Route::post('/logout', [Login_Controller::class, 'logout'])->name('logout');
});

// Routes for technical-support and administrator (protected by 'auth' middleware)
Route::middleware(['auth', \App\Http\Middleware\UpdateLastActivity::class])->group(function () {
    // Routes that require authentication
    Route::get('/home', [Home_Controller::class, 'showHome'])->name('home');
    Route::get('/ticket', [Ticket_Controller::class, 'showTicket'])->name('ticket'); // GET request for displaying the form
    Route::post('/ticket', [Ticket_Controller::class, 'store'])->name('ticket.store'); // POST request for submitting the form
    Route::get('/filter-tickets/{status}', [Ticket_Controller::class, 'filterTickets']);

    Route::get('/device_management', [Device_Management_Controller::class, 'showDevice_Management'])->name('device_management');
    Route::get('/user_management', [User_Management_Controller::class, 'showUser_Management'])->name('user_management');
    Route::get('/report', [Report_Controller::class, 'showReport'])->name('report');
    Route::get('/audit_logs', [Audit_logs_Controller::class, 'showAudit_logs'])->name('audit_logs');
});
