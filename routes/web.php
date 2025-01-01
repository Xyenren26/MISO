<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home_Controller;
use App\Http\Controllers\Ticket_Controller;
use App\Http\Controllers\Device_Management_Controller;

Route::get('/home', [Home_Controller::class, 'showHome'])->name('home');

Route::get('/ticket', [Ticket_Controller::class, 'showTicket'])->name('ticket');

Route::get('/device_management', [Device_Management_Controller::class, 'showDevice_Management'])->name('device_management');