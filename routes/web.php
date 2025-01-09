<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home_Controller;
use App\Http\Controllers\Ticket_Controller;
use App\Http\Controllers\Device_Management_Controller;
use App\Http\Controllers\User_Management_Controller;
use App\Http\Controllers\Audit_logs_Controller;
use App\Http\Controllers\Report_Controller;

Route::get('/home', [Home_Controller::class, 'showHome'])->name('home');

Route::get('/ticket', [Ticket_Controller::class, 'showTicket'])->name('ticket');

Route::post('/ticket/create', [Ticket_Controller::class, 'createTicket'])->name('ticket.create');

Route::get('/tickets/filter/{status}', [Ticket_Controller::class, 'filterTickets']);

Route::get('/device_management', [Device_Management_Controller::class, 'showDevice_Management'])->name('device_management');

Route::get('/user_management', [User_Management_Controller::class, 'showUser_Management'])->name('user_management');

Route::get('/report', [Report_Controller::class, 'showReport'])->name('report');

Route::get('/audit_logs', [Audit_logs_Controller::class, 'showAudit_logs'])->name('audit_logs');