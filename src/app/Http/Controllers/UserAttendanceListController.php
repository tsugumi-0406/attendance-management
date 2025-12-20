<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAttendanceListController extends Controller
{
    public function list(Request $request)
    {
        return view('user_attendance_list');
    }
}
