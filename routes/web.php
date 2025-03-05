<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\Login_Controller;
use App\Http\Controllers\Signup_Controller;
use App\Http\Controllers\AccountSecurityController;
use App\Http\Controllers\Home_Controller;
use App\Http\Controllers\EndUserController;
use App\Http\Controllers\Ticket_Controller;
use App\Http\Controllers\Device_Management_Controller;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\Profile_Controller;
use App\Http\Controllers\User_Management_Controller;
use App\Http\Controllers\Audit_logs_Controller;
use App\Http\Controllers\Report_Controller;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\OtherMessagesController;



use App\Http\Controllers\PDFController;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes();


Route::get('/generate-qr/{form_no}', function ($form_no) {
    return response(QrCode::size(200)->generate(route('generate.pdf', $form_no)))
        ->header('Content-Type', 'image/svg+xml');
});

Route::get('/generate-qr-deployment/{control_number}', function ($control_number) {
    return response(QrCode::size(200)->generate(route('generate.deployment.pdf', $control_number)))
        ->header('Content-Type', 'image/svg+xml');
});

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
    Route::get('/notifications', [NotificationController::class, 'fetchNotifications']);
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    Route::get('/employee/home', [EndUserController::class, 'index'])->name('employee.home');
    Route::get('/employee/ticket', [EndUserController::class, 'showEmployeeTicket'])->name('employee.tickets');
    Route::get('/employee/filter', [EndUserController::class, 'filterEmployeeTickets']);
    Route::get('/track-device-status/{ticket_id}', [EndUserController::class, 'trackDeviceStatus']);

    Route::get('/home', [Home_Controller::class, 'showHome'])->middleware('CustomAuthenticate')->name('home');
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
    Route::get('/get-deployment-names/{control_no}', [Ticket_Controller::class, 'getDeploymentNames']);
    Route::post('/tickets/{control_no}/archive', [Ticket_Controller::class, 'archiveTicket']);
    Route::post('/submit-rating', [RatingController::class, 'store'])->name('rating.store');
    Route::get('/get-qr-code/{form_no}', [ServiceRequestController::class, 'getQrCode']);

    Route::post('/technical-reports/store', [Ticket_Controller::class, 'storeTechnicalReport'])->name('technical-reports.store');
    Route::get('/device_management', [Device_Management_Controller::class, 'showDevice_Management'])->name('device_management');
    Route::get('/fetch/records', [Device_Management_Controller::class, 'getFilteredRecords'])->name('fetch.records');

    Route::get('/service-request/{form_no}', [ServiceRequestController::class, 'getServiceRequest']);

    // In web.php
    Route::get('/check-service-request/{ticketId}', [ServiceRequestController::class, 'checkServiceRequest']);
    Route::post('/service-request', [ServiceRequestController::class, 'store'])->name('service.request.store');
    Route::post('/update-status/{form_no}', [ServiceRequestController::class, 'updateStatus']);
    Route::post('/update-service-request', [ServiceRequestController::class, 'update']);
    Route::post('/deployments', [DeploymentController::class, 'store'])->name('deployments.store');
    Route::get('/check-deployment/{control_no}', [DeploymentController::class, 'checkDeployment']);
    Route::get('/deployment/view/{control_no}', [DeploymentController::class, 'showDeployment']);

    Route::get('/profile/complete', [Profile_Controller::class, 'showCompleteProfileForm'])->name('profile.complete.form');
    Route::post('/profile/complete', [Profile_Controller::class, 'completeProfile'])->name('profile.complete');
    Route::get('/profile', [Profile_Controller::class, 'index'])->name('profile.index');
    // Update profile data
    Route::post('/profile/update', [Profile_Controller::class, 'update'])->name('profile.update');   
    Route::get('/account/security', [AccountSecurityController::class, 'index'])->name('account.security');
    Route::post('/account/security/change-password', [AccountSecurityController::class, 'changePassword'])->name('account.changePassword');
    Route::post('/account/security/change-email', [AccountSecurityController::class, 'changeEmail'])->name('account.changeEmail');
    Route::post('/account/verify-password', [AccountSecurityController::class, 'verifyPassword'])->name('account.verifyPassword');
    
    Route::middleware(['auth', 'CheckAdmin'])->group(function () {
        Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.index');
        Route::get('/user_management', [User_Management_Controller::class, 'showUser_Management'])->name('user_management');
        Route::get('/user/management', [User_Management_Controller::class, 'showUser_Management'])->name('user.management');
        Route::post('/user/update/{employee_id}', [User_Management_Controller::class, 'update'])->name('user.update');
        Route::post('/user/change-role/{employee_id}', [User_Management_Controller::class, 'changeRole'])->name('user.changeRole');
        Route::patch('/user/toggle-status/{employee_id}', [User_Management_Controller::class, 'toggleStatus'])->name('user.toggleStatus');
        Route::get('/report', [Report_Controller::class, 'showReport'])->name('report');
        Route::get('/export-technician-performance', [Report_Controller::class, 'exportTechnicianPerformance']);
    });
    Route::get('/audit_logs', [Audit_logs_Controller::class, 'showAudit_logs'])->name('audit_logs');

    Route::post('/approve-ticket', [ApprovalController::class, 'approveTicket'])->name('approve.ticket');
    Route::get('/get-approval-details', [ApprovalController::class, 'getApprovalDetails']);

    Route::post('/send-message/{ticketId}', [EndUserController::class, 'sendMessageToTechnicalSupport']);
    Route::post('/send-message-technical/{ticketId}', [Ticket_Controller::class, 'sendMessageToEndUser']);
    Route::get('/message/{id}', [OtherMessagesController::class, 'index'])->name('chatify');
    Route::get('/unseen-messages', [OtherMessagesController::class, 'getUnseenMessages']);

    //faq route
     Route::get('/faq', function () {
         return view('faq');
     })->name('faq');
});