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

        // 基準月の設定
        $list_day = $year_month . "-01";

        // 翌月・先月用のリンク用の日付作成
        $link_day_before = Carbon::parse($list_day)->subMonth()->format('Y-m');
        $link_day_after = Carbon::parse($list_day)->addMonth()->format('Y-m');

        $base_date = Carbon::parse($list_day);
        $base_date_next = Carbon::parse($list_day);
        $next_date = $base_date_next->addMonth()->subDay();
        
        // 表示する日付の範囲用の値の設定
        $firstDateOfMonth = $base_date;  
        $lastDateOfMonth = $next_date;

        $works = Work::where('user_id', $user->id)
                ->whereBetween('date', [$firstDateOfMonth, $lastDateOfMonth])
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

        $breaks = BreakTime::where('user_id', $user->id)
            ->whereBetween('date', [$firstDateOfMonth, $lastDateOfMonth])
            ->get();

        $break_date = [];
        foreach($breaks as $break){
            $date = $break['date'];

            if(empty( $break_date[ $date ] ))
                $break_date[$date] = 0;

            // 休憩終了がない場合   
            if($break['stop'] == null){}
            else{
                $start_date_time = $date . ' ' . $break['start'];
                $stop_date_time = $date . ' ' . $break['stop']; 
                $start_time = Carbon::parse($start_date_time);
                $end_time = Carbon::parse($stop_date_time); 
                $diffInSeconds = $start_time->diffInSeconds($end_time);

                $break_date[$date] += $diffInSeconds;   
            }
        }
  
        return view('user_attendance_list', compact('works', 'breaks', 'dd', 'user', 'base_date', 'link_day_before', 'link_day_after', 'break_date'));
    }
}

