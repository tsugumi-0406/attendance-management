<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\BreakTime;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;


class UserAttendanceListController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::user();
        // $month = $request->Carbon::parse();

        $works = Work::where('user_id', $user->id)
                ->get();

        $breaks = BreakTime::where('user_id', $user->id)
                ->get();

        $dd = array(
            '0' => '日',
            '1' => '月',
            '2' => '火',
            '3' => '水',
            '4' => '木',
            '5' => '金',
            '6' => '土'
            );

        return view('user_attendance_list', compact('works', 'breaks', 'dd', 'user'));
    }
}

