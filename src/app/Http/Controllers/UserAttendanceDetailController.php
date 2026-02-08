<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\BreakTime;
use App\Models\User;
use App\Models\UnapprovedWork;
use App\Models\UnapprovedBreak;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserAttendanceDetailRequest;

class UserAttendanceDetailController extends Controller
{
    // 勤怠詳細画面（一般ユーザー）の表示
    public function detail($id)
    {
        $work = Work::where('id', $id)
                ->firstOrFail();

        $user = User::where('id', $work->user_id)
                ->first();
        
        $breaks = BreakTime::where('user_id', $user->id)
            ->where('date', $work->date)
            ->get();

        $unapproved_work = UnapprovedWork::where('work_id', $id)
                ->first();

        $unapproved_breaks = UnapprovedBreak::where('user_id', $user->id)
            ->where('date', $work->date)
            ->get();

        return view('user_attendance_detail', compact('work', 'user', 'breaks', 'unapproved_work', 'unapproved_breaks'));
    }

    // 勤怠修正申請を出す
    public function apply(UserAttendanceDetailRequest $request)
    {
        $work = Work::findOrFail($request->work_id);

        $targetUserId = $work->user_id;

        $work_id = $work->id;
        $user_id = $work->user_id;
        $date    = $work->date;

        UnapprovedWork::create([
            'work_id' => $work->id,
            'user_id' => $user_id,
            'date' => $work->date,
            'attendance' => $request->attendance,
            'leaving' => $request->leaving,
            'remarks' => $request->remarks,
        ]);

        Work::find($work_id)->update([
            'update' => 'pending',
        ]);

        $break_datas = $request->break_requests;

        if($break_datas == null){

        }else{
            foreach($break_datas as $break_data){
                $break_id = $break_data['break_id'];

                $start = $break_data['start'];

                if($break_data['stop'] == '--:--'){
                    $stop = null;
                }else{
                    $stop = $break_data['stop'];
                }

                UnapprovedBreak::create([
                    'break_id' => $break_id,
                    'user_id' => $user_id,
                    'date' => $date,
                    'start' => $start,
                    'stop' => $stop,
                ]);

                BreakTime::find($break_id)->update([
                    'update' => 'pending',
                ]);
            }
        }

        $from = $request->input('from');

        return redirect('/attendance/detail/' . $work_id);
    }
}
