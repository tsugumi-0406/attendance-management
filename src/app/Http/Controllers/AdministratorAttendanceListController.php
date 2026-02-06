<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\BreakTime;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;

class AdministratorAttendanceListController extends Controller
{
    public function attendancelist(Request $request)
    {
        $now = CarbonImmutable::now();

        // 基準日の設定
        if($request->query('day') === null)
            $list_day = $now->format('Y-m-d');
        else{
            $list_day = $request->query('day');
        }
        $base_date = Carbon::parse($list_day);

        // 翌日・先日用のリンク用の日付作成
        $link_day_before = Carbon::parse($list_day)->subDay()->format('Y-m-d');
        $link_day_after = Carbon::parse($list_day)->addDay()->format('Y-m-d');

        $works = Work::where('date', $base_date)
                ->get();

        $breaks = BreakTime::where('date', $base_date)
                ->get();

        $user = Work::with('user');

        $break_seconds_by_user = [];

        foreach ($breaks as $break) {
            $uid = $break->user_id;

            $break_seconds_by_user[$uid] ??= 0;

            // stop が無い（休憩中）なら加算しない
            if ($break->stop === null) continue;

            $date = Carbon::parse($break->date)->format('Y-m-d');
            $start = Carbon::parse($date . ' ' . $break->start);
            $end   = Carbon::parse($date . ' ' . $break->stop);

            $break_seconds_by_user[$uid] += $start->diffInSeconds($end);
        }
        
        return view('administrator_attendance_list', compact('base_date', 'works', 'link_day_before', 'link_day_after', 'user', 'break_seconds_by_user'));
    }
}
