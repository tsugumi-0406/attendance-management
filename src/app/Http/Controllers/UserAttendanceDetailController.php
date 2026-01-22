<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\BreakTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserAttendanceDetailController extends Controller
{
    public function detail($id)
    {
        $works = Work::where('id', $id)
                ->first();

        $user = User::where('id', $works->user_id)
                ->first();
        
        $breaks = BreakTime::where('user_id', $user->id)
            ->where('date', $works->date)
            ->get();

        return view('user_attendance_detail', compact('works', 'user', 'breaks'));
    }

    public function apply(Request $request)
    {
        return redirect /attendance/detail/{id}
    }
}
