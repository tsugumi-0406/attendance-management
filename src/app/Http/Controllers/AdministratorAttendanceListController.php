<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministratorAttendanceListController extends Controller
{
    public function attendancelist(Request $request)
    {
        return view('administrator_attendance_list');
    }
}
