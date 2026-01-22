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

        return view('user_attendance_detail', compact('works', 'user', 'breaks'));
    }

    public function apply(Request $request)
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

        return redirect('/attendance/detail/' . $work_id);
    }
}
