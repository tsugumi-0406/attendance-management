<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use App\Models\Work;

class AttendanceRegisterController extends Controller
{
    public function attendance(Request $request)
    {
        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        // $time = $now->time;
        $user = Auth::user();
        $working = Work::where('user_id', $user->id)
                ->where('date', $date)
                ->first();
        // dd($working);
        dd($user->id);

        // 退勤済
        if(退勤時間がDBにある){
            $status = 'finished';
        // 休憩中
        } elseif (休憩開始時間がDBにある and 休憩終了時間がDBにない) {
            $status = 'breaking';
        // 出勤中
        } elseif (出勤時間がDBにある and 退勤時間がDBにない) {
            $status = 'working';
        // 勤務外
        } else {
            $status = 'off';
        }

        

        return view('attendance_register', compact('date', 'status'));
    }
}
