<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\BreakTime;
use App\Models\User;
use App\Models\UnapprovedWork;
use App\Models\UnapprovedBreak;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function approval(Request $request, $work_id)
    {
        $work = Work::where('id', $work_id)
                ->first();

        $user = User::where('id', $work->user_id)
                ->first();
        
        $breaks = BreakTime::where('user_id', $user->id)
            ->where('date', $work->date)
            ->get();

        $unapproved_work = UnapprovedWork::where('id', $work_id)
                ->first();

        $unapproved_breaks = UnapprovedBreak::where('user_id', $user->id)
            ->where('date', $work->date)
            ->get();

        return view('approval', compact('work', 'user', 'breaks', 'unapproved_work', 'unapproved_breaks'));
    }

    public function approveWork(Request $request)
    {
        $work_id = $request->work_id;

        $unapproved_work = UnapprovedWork::where('work_id', $work_id)
                ->first();
        $attendance = $unapproved_work->attendance;
        $leaving = $unapproved_work->leaving;
        $remarks = $unapproved_work ->remarks;
        $application_date = $unapproved_work->created_at;

        Work::find($work_id)->update([
            'attendance' => $attendance,
            'leaving' => $leaving,
            'remarks' => $remarks,
            'update' => 'done',
            'application_date' => $application_date
        ]);

        UnapprovedWork::where('work_id', $work_id)->delete();

        $break_datas = $request->break_requests;

        if($break_datas == null){

        }else{
            foreach($break_datas as $break_data){
                $unapprovedbreak_id = $unapproved_break->id;

                $unapproved_break = UnapprovedBreak::where('break_id', $unapprovedbreak_id )
                                ->first();
                $start = $unapproved_break->start;
                $stop = $unapproved_break->stop;
                $application_date = $unapproved_break->created_at;
                $break_id = $unapproved_break->break_id;

                BreakTime::find($break_id)->update([
                    'start' => $start,
                    'stop' => $stop,
                    'update' => 'done',
                    'application_date' => $application_date
                ]);

                UnapprovedBreak::where('work_id', $work_id)->delete();
            }
        }

        return redirect('/admin/stamp_correction_request/approve/' . $work_id);
    }
}
