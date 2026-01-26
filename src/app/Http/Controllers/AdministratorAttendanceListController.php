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
        
        return view('administrator_attendance_list', compact('base_date', 'works', 'link_day_before', 'link_day_after', 'user'));
    }
}
