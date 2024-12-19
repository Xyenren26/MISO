<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home_Controller;

Route::get('/home', [Home_Controller::class, 'showHome'])->name('home');
