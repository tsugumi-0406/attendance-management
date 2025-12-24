<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministratorAttendanceDetailController extends Controller
{
    public function detail(Request $request)
    {
        return view('administrator_attendance_detail');
    }
}
