<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAttendanceDetailController extends Controller
{
    public function detail(Request $request)
    {
        return view('user_attendance_detail');
    }
}
