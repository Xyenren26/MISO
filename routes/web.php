<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Login_Controller;
use App\Http\Controllers\Signup_Controller;
use App\Http\Controllers\Home_Controller;
use App\Http\Controllers\Ticket_Controller;
use App\Http\Controllers\Device_Management_Controller;
use App\Http\Controllers\Profile_Controller;
use App\Http\Controllers\User_Management_Controller;
use App\Http\Controllers\Audit_logs_Controller;
use App\Http\Controllers\Report_Controller;
use App\Http\Controllers\ServiceRequestController;


Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Auth::routes(); 

Route::middleware(['web'])->group(function() {
    // Routes accessible only to unauthenticated users
    Route::middleware(['guest'])->group(function () {
        Route::get('/', [Login_Controller::class, 'showLogin'])->name('login');
        Route::get('/login', [Login_Controller::class, 'showLogin']);
        Route::get('/signup', [Login_Controller::class, 'showSignup'])->name('signup');
        Route::post('/signup', [Signup_Controller::class, 'storeSignup'])->name('signup.store');
    });
    
    Route::middleware('ClearExpiredSession')->post('/login', [Login_Controller::class, 'authenticate'])->name('login.authenticate');
    
    // Logout route
    Route::post('/logout', [Login_Controller::class, 'logout'])->name('logout');
});



// Routes for technical-support and administrator (protected by 'auth' middleware)
Route::middleware(['auth', \App\Http\Middleware\UpdateLastActivity::class])->group(function () {
    // Routes that require authentication
    Route::get('/home', [Home_Controller::class, 'showHome'])->name('home');
    Route::get('/ticket', [Ticket_Controller::class, 'showTicket'])->name('ticket'); // GET request for displaying the form
    Route::post('/ticket', [Ticket_Controller::class, 'store'])->name('ticket.store'); // POST request for submitting the form
    Route::get('/tickets/filter', [Ticket_Controller::class, 'filterTickets'])->name('tickets.filter');
    // Route for fetching ticket details by control_no
    Route::get('/ticket-details/{control_no}', [Ticket_Controller::class, 'show']);
    Route::post('/api/pass-ticket', [Ticket_Controller::class, 'passTicket']);
    Route::post('/tickets/update-remarks', [Ticket_Controller::class, 'updateRemarks'])->name('tickets.updateRemarks');
    Route::get('/endorsment-details/{control_no}', [Ticket_Controller::class, 'createEndorsement']);
    Route::post('/endorsements/store', [Ticket_Controller::class, 'endorseStore'])->name('endorsements.store');
    Route::get('/endorsement-details/{ticketId}', [Ticket_Controller::class, 'getEndorsementDetails']);
    Route::get('/device_management', [Device_Management_Controller::class, 'showDevice_Management'])->name('device_management');
    Route::get('/service-request/{form_no}', [Device_Management_Controller::class, 'getServiceRequest']);

    // In web.php
    Route::get('/service-request', [ServiceRequestController::class, 'create']);
    Route::post('/service-request', [ServiceRequestController::class, 'store'])->name('service.request.store');

    Route::get('/profile/complete', [Profile_Controller::class, 'showCompleteProfileForm'])->name('profile.complete.form');
    Route::post('/profile/complete', [Profile_Controller::class, 'completeProfile'])->name('profile.complete');
    Route::get('/user_management', [User_Management_Controller::class, 'showUser_Management'])->name('user_management');
    Route::get('/report', [Report_Controller::class, 'showReport'])->name('report');
    Route::get('/audit_logs', [Audit_logs_Controller::class, 'showAudit_logs'])->name('audit_logs');
});
