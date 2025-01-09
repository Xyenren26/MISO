<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Report_Controller extends Controller
{
    public function showReport(){
        //View The Report Blade
        return view('Report');
        }
}
