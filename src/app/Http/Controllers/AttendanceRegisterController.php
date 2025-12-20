<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceRegisterController extends Controller
{
    public function attendance(Request $request)
    {
        return view('attendance_register');
    }
}
