<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Login_Controller extends Controller
{
    public function showLogin(){
        return view('Login');
    } 
    
    public function showSignup(){
        return view('Signup');
    }
}
