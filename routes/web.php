<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Login_Controller;
use App\Http\Controllers\Signup_Controller;
use App\Http\Controllers\AccountSecurityController;
use App\Http\Controllers\Home_Controller;
use App\Http\Controllers\Ticket_Controller;
use App\Http\Controllers\Device_Management_Controller;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\Profile_Controller;
use App\Http\Controllers\User_Management_Controller;
use App\Http\Controllers\Audit_logs_Controller;
use App\Http\Controllers\Report_Controller;

use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\ChatController;


use App\Http\Controllers\PDFController;

Route::get('/generate-pdf/{form_no}', [PDFController::class, 'generatePDF'])->name('generate.pdf');
Route::get('/generate-deployment-pdf/{control_number}', [PDFController::class, 'generateDeploymentPDF'])->name('generate.deployment.pdf');

Route::post('verification/send', [VerificationController::class, 'sendVerificationEmail'])
    ->middleware('auth')
    ->name('verification.send');


Route::get('verification/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])
    ->name('verification.custom.verify');

Route::get('verification/email/{id}/{hash}', [VerificationController::class, 'RegistrationEmailValidate'])
    ->name('RegistrationEmailValidate')
    ->middleware('signed');;

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


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
    Route::get('/endorsement/details/{ticketId}', [Ticket_Controller::class, 'getTicketDetails']);
    Route::get('/technical-reports/check/{control_no}', [Ticket_Controller::class, 'checkTechnicalReport']);
    Route::get('/get-ticket-details/{control_no}', [Ticket_Controller::class, 'getTechnicalReportDetails']);
    Route::post('/technical-reports/store', [Ticket_Controller::class, 'storeTechnicalReport'])->name('technical-reports.store');
    Route::get('/device_management', [Device_Management_Controller::class, 'showDevice_Management'])->name('device_management');
    Route::get('/service-request/{form_no}', [Device_Management_Controller::class, 'getServiceRequest']);

    // In web.php
    Route::get('/check-service-request/{ticketId}', [ServiceRequestController::class, 'checkServiceRequest']);
    Route::post('/service-request', [ServiceRequestController::class, 'store'])->name('service.request.store');
    Route::post('/update-status/{form_no}', [ServiceRequestController::class, 'updateStatus']);
    Route::post('/deployments', [DeploymentController::class, 'store'])->name('deployments.store');
    Route::get('/deployment/view/{id}', [Device_Management_Controller::class, 'showDeployment']);

    Route::get('/profile/complete', [Profile_Controller::class, 'showCompleteProfileForm'])->name('profile.complete.form');
    Route::post('/profile/complete', [Profile_Controller::class, 'completeProfile'])->name('profile.complete');
    Route::get('/profile', [Profile_Controller::class, 'index'])->name('profile.index');
    // Update profile data
    Route::post('/profile/update', [Profile_Controller::class, 'update'])->name('profile.update');   
    Route::get('/account/security', [AccountSecurityController::class, 'index'])->name('account.security');
    Route::post('/account/security/change-password', [AccountSecurityController::class, 'changePassword'])->name('account.changePassword');
    Route::post('/account/security/change-email', [AccountSecurityController::class, 'changeEmail'])->name('account.changeEmail');
    Route::post('/account/verify-password', [AccountSecurityController::class, 'verifyPassword'])->name('account.verifyPassword');
    Route::get('/user_management', [User_Management_Controller::class, 'showUser_Management'])->name('user_management');
    Route::get('/report', [Report_Controller::class, 'showReport'])->name('report');
    Route::get('/audit_logs', [Audit_logs_Controller::class, 'showAudit_logs'])->name('audit_logs');

    //Gawa ni Rogelio
    // Route for displaying user management page
    Route::get('/user-management', [User_Management_Controller::class, 'showUser_Management'])->name('user.management');
    
    // Route for editing a user
    Route::get('/user/edit/{employee_id}', [User_Management_Controller::class, 'editUser'])->name('user.edit');

    // Define the route to handle POST requests for editing user info
    Route::post('/user/edit/{employee_id}', [User_Management_Controller::class, 'update'])->name('user.edit');

    // Route for deleting a user
    Route::delete('/user/delete/{employee_id}', [User_Management_Controller::class, 'deleteUser'])->name('user.delete');

    //Route for updating user 
    Route::patch('/user/update/{employee_id}', [User_Management_Controller::class, 'updateUser'])->name('user.update');
    
    //Routes for disable user status
    Route::patch('/user/disable/{employee_id}', [User_Management_Controller::class, 'disable'])->name('user.disable');

    //Route for enable user status
    Route::patch('/user/toggle-status/{employee_id}', [User_Management_Controller::class, 'toggleStatus'])->name('user.toggleStatus');

    //For messaging

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send-message', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/fetch-messages/{receiverId}', [ChatController::class, 'fetchMessages'])->name('chat.fetch');

    //Chat functionality 
    Route::get('/api/unread-messages', [ChatController::class, 'getUnreadMessages']);
    Route::get('/chat/unread-count', [ChatController::class, 'getUnreadMessageCount']);
    Route::post('/chat/mark-as-read/{senderId}', [ChatController::class, 'markMessagesAsRead']);
    Route::post('/chat/mark-read/{receiverId}', [ChatController::class, 'markAsRead']);
    Route::get('/chat/active-users', [ChatController::class, 'getActiveUsers']);

//for status update if online of offline
    Route::get('/get-user-status', function (Request $request) {
        $users = DB::table('users')->select('employee_id', 'last_activity')->get();

        $onlineUsers = [];
        foreach ($users as $user) {
            if ($user->last_activity && Carbon::parse($user->last_activity)->diffInMinutes(now()) < 5) {
                $onlineUsers[] = $user->employee_id; // Online if active within 5 minutes
            }
        }

        return response()->json(['onlineUsers' => $onlineUsers]);
    });

    

    
});