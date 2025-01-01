<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceManagement; // Import the model


class Device_Management_Controller extends Controller
{
    public function showDevice_Management()
{
    $devices = DeviceManagement::paginate(10); // Fetch 10 records per page
    return view('Device_Management', compact('devices'));

    // Pass the data to the view
    return view('Device_Management', ['devices' => $devices]);
}

}
