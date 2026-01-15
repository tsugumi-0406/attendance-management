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
}
