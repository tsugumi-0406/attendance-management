<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffAttendanceListController extends Controller
{
    public function list(Request $request)
    {
        return view('staff_attendance_list');
    }
}
