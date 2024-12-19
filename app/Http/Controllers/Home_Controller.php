<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class Home_Controller extends Controller
{
    public function showHome(){
    //View The Home Blade
    return view('home');
    }
}
