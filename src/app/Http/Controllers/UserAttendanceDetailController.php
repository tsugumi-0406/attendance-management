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

        $unapproved_works = UnapprovedWork::where('id', $id)
                ->first();

        $unapproved_breaks = UnapprovedBreak::where('user_id', $user->id)
            ->where('date', $works->date)
            ->get();

        return view('user_attendance_detail', compact('works', 'user', 'breaks', 'unapproved_works', 'unapproved_breaks'));
    }

    public function apply(UserAttendamceDetailRequest $request)
    {
        $work_id = $request->work_id;

        $user = Auth::user();

        $work = Work::where('id', $work_id)
                ->first();
        $date = $work->date;

        $attendance = $request->attendance;

        $leaving = $request->leaving;

        $remarks = $request->remarks;

        UnapprovedWork::create([
            'work_id' => $work_id,
            'user_id' => $user->id,
            'date' => $date,
            'attendance' => $attendance,
            'leaving' => $leaving,
            'remarks' => $remarks,
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
                    'user_id' => $user->id,
                    'date' => $date,
                    'start' => $start,
                    'stop' => $stop,
                ]);

                BreakTime::find($break_id)->update([
                    'update' => 'pending',
                ]);
            }
        }


        return redirect('/attendance/detail/' . $work_id);
    }
}
