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
        $now = CarbonImmutable::now();
        if($request->query('month') === null)
            $year_month = $now->format('Y-m');
        else{
            $year_month = $request->query('month');
        }

        // 2026-01-01
        $list_day = $year_month . "-01";

        $link_day_before = Carbon::parse($list_day)->subMonth()->format('Y-m');
        $link_day_after = Carbon::parse($list_day)->addMonth()->format('Y-m');

        // 2026-01-01のCarbon
        $base_date = Carbon::parse($list_day);
        $base_date_next = Carbon::parse($list_day);
        $next_date = $base_date_next->addMonth()->subDay();
        
        $firstDateOfMonth = $base_date;  
        $lastDateOfMonth = $next_date;

        $works = Work::where('user_id', $user->id)
                ->whereBetween('date', [$firstDateOfMonth, $lastDateOfMonth])
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

        return view('user_attendance_list', compact('works', 'breaks', 'dd', 'user', 'base_date', 'link_day_before', 'link_day_after'));
    }
}

