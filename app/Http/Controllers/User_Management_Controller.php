<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Import the model

class User_Management_Controller extends Controller
{
    public function showUser_Management(){
        $users = User::all(); // Fetch all users
        return view('user_management', ['users' => $users]);
    }
}
