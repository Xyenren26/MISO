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

Route::middleware(['web'])->group(function() {
Route::get('/', [Login_Controller::class, 'showLogin'])->name('login');
Route::get('/login', [Login_Controller::class, 'showLogin']);
Route::post('/login', [Login_Controller::class, 'authenticate'])->name('login.authenticate');

Route::get('/signup', [Login_Controller::class, 'showSignup'])->name('signup');
Route::post('/signup', [Signup_Controller::class, 'storeSignup'])->name('signup.store');
});

// Routes for technical-support and administrator (protected by 'auth' middleware)
Route::middleware(['auth', \App\Http\Middleware\UpdateLastActivity::class])->group(function () {
    // Routes that require authentication
    Route::get('/home', [Home_Controller::class, 'showHome'])->name('home');
    Route::get('/ticket', [Ticket_Controller::class, 'showTicket'])->name('ticket');
    Route::post('/ticket/create', [Ticket_Controller::class, 'createTicket'])->name('ticket.create');
    Route::get('/device_management', [Device_Management_Controller::class, 'showDevice_Management'])->name('device_management');
    Route::get('/user_management', [User_Management_Controller::class, 'showUser_Management'])->name('user_management');
    Route::get('/report', [Report_Controller::class, 'showReport'])->name('report');
    Route::get('/audit_logs', [Audit_logs_Controller::class, 'showAudit_logs'])->name('audit_logs');
});
