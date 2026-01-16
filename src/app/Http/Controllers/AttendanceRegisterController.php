<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use App\Models\Work;
use App\Models\BreakTime;

class AttendanceRegisterController extends Controller
{
    // 出勤画面の表示
    public function attendance(Request $request)
    {
        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        $user = Auth::user();
        $working = Work::where('user_id', $user->id)
                ->where('date', $date)
                ->first();

        $breaking = BreakTime::where('user_id', $user->id)
                ->where('date', $date)
                ->orderBy('start', 'desc')
                ->first();

        if($working != null){
            $attending = $working->attendance;
            $leaving = $working->leaving;
            if($breaking != null){
                $breaking_start = $breaking->start;
                $breaking_stop = $breaking->stop;
            } else {
                $breaking_start = null;
                $breaking_stop = null;
            }

            // 退勤済
            if($leaving != null){
                $status = 'finished';
            // 休憩中
            } elseif ($breaking_start != null && $breaking_stop == null) {
                $status = 'breaking';
            // 出勤中
            } elseif ($attending != null and $leaving === null) {
                $status = 'working';
            // 勤務外
            } else {
                $status = 'off';
            }
        } else {
                $status = 'off';
        }

        return view('attendance_register', compact('now', 'status'));
    }




    // 出勤を打刻する
    public function stampAttendance(Request $request)
    {
        $user = Auth::user();
        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        Work::create([
            'user_id'    => $user->id,
            'date' => $date,
            'attendance' => $time,
            'update' => 'no',
        ]);
        return redirect('/attendance');
    }




    // 休憩開始を打刻する
    public function stampBreakStart(Request $request)
    {
        $user = Auth::user();
        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        // 出退勤記録を取得
        $working = Work::where('user_id', $user->id)
                ->where('date', $date)
                ->first();

        // 休憩記録を取得
        $breaking = BreakTime::where('user_id', $user->id)
                ->where('date', $date)
                ->orderBy('start', 'desc')
                ->first();

        
        if($working != null){
            $attending = $working->attendance;
            $leaving = $working->leaving;
            if($breaking != null){
                $breaking_start = $breaking->start;
                $breaking_stop = $breaking->stop;
            } else {
                $breaking_start = null;
                $breaking_stop = null;
            }
        } else {
                return redirect('/attendance')->with('message', '出勤記録がありません');
        }

        // 出勤してないとき
        if($attending === null){
            return redirect('/attendance')->with('message', '出勤記録がありません');
        }
        // 退勤済の時
        if($leaving != null){
            return redirect('/attendance')->with('message', '既に退勤済です');
        }
        // 休憩中の時
        if($breaking_start != null && $breaking_stop == null){
            return redirect('/attendance')->with('message', '既に休憩中です');
        }

        BreakTime::create([
            'user_id'    => $user->id,
            'date' => $date,
            'start' => $time,
            'update' => 'no',
        ]);

        return redirect('/attendance');
    }




    // 休憩終了を打刻する
    public function stampBreakStop(Request $request)
    {
        $user = Auth::user();
        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        // 出退勤記録を取得
        $working = Work::where('user_id', $user->id)
                ->where('date', $date)
                ->first();

        // 休憩記録を取得
        $breaking = BreakTime::where('user_id', $user->id)
                ->where('date', $date)
                ->orderBy('start', 'desc')
                ->first();

        
        if($working != null){
            $attending = $working->attendance;
            $leaving = $working->leaving;
            if($breaking != null){
                $breaking_stop = $breaking->stop;
            } else {
                return redirect('/attendance')->with('message', '休憩開始記録がありません');
            }
        } else {
                return redirect('/attendance')->with('message', '出勤記録がありません');
        }

        // 出勤してないとき
        if($attending === null){
            return redirect('/attendance')->with('message', '出勤記録がありません');
        }
        // 退勤済の時
        if($leaving != null){
            return redirect('/attendance')->with('message', '既に退勤済です');
        }
        // 休憩中ではない時
        if($breaking_stop != null){
            return redirect('/attendance')->with('message', '休憩中ではありません');
        }

        $breaking->update(['stop' => $time]);

        return redirect('/attendance');
    }



    // 退勤を打刻する
    public function stampLeave(Request $request)
    {
        $user = Auth::user();
        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        // 出退勤記録を取得
        $working = Work::where('user_id', $user->id)
                ->where('date', $date)
                ->first();

        // 休憩記録を取得
        $breaking = BreakTime::where('user_id', $user->id)
                ->where('date', $date)
                ->orderBy('start', 'desc')
                ->first();

        
        if($working != null){
            $attending = $working->attendance;
            $leaving = $working->leaving;
            if($breaking != null){
                $breaking_stop = $breaking->stop;
                $breaking_start = $breaking->start;
            } else {
                $breaking_stop = null;
                $breaking_start = null;
            }
        } else {
                return redirect('/attendance')->with('message', '出勤記録がありません');
        }

        // 出勤してないとき
        if($attending === null){
            return redirect('/attendance')->with('message', '出勤記録がありません');
        }
        // 退勤済の時
        if($leaving != null){
            return redirect('/attendance')->with('message', '既に退勤済です');
        }
        // 休憩中の時
        if($breaking_start != null && $breaking_stop == null){
            return redirect('/attendance')->with('message', '休憩中です');
        }

        $working->update(['leaving' => $time]);

        return redirect('/attendance');
    }
}
