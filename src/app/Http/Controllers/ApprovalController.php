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
    // 承認画面表示
    public function approval(Request $request, $work_id)
    {
        $work = Work::where('id', $work_id)
                ->first();

        $user = User::where('id', $work->user_id)
                ->first();
        
        $breaks = BreakTime::where('user_id', $user->id)
            ->where('date', $work->date)
            ->get();

        $unapproved_work = UnapprovedWork::where('work_id', $work_id)
                ->first();

        $unapproved_breaks = UnapprovedBreak::where('user_id', $user->id)
            ->where('date', $work->date)
            ->get();

        return view('approval', compact('work', 'user', 'breaks', 'unapproved_work', 'unapproved_breaks'));
    }

    // 承認する
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
                $break_id = $break_data['break_id'] ?? null;
                if (!$break_id) {
                    continue;
                }

                $unapproved_break = UnapprovedBreak::where('break_id', $break_id)->first();
                if (!$unapproved_break) {
                    continue; // 申請が無ければスキップ（または abort(404)）
                }

                BreakTime::find($break_id)->update([
                    'start' => $unapproved_break->start,
                    'stop' => $unapproved_break->stop,
                    'update' => 'done',
                    'application_date' => $unapproved_break->created_at,
                ]);

                UnapprovedBreak::where('break_id', $break_id)->delete();
            }
        }

        return redirect('/stamp_correction_request/approve/' . $work_id);
    }
}
