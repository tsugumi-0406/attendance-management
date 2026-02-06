<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\BreakTime;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Http\Requests\UserAttendanceDetailRequest;


class AdministratorAttendanceDetailController extends Controller
{
    public function detail($id)
    {
        $work = Work::where('id', $id)
                ->firstOrFail();

        $user = User::where('id', $work->user_id)
                ->first();
        
        $breaks = BreakTime::where('user_id', $user->id)
            ->where('date', $work->date)
            ->get();

        return view('administrator_attendance_detail',compact('work', 'user', 'breaks'));
    }



    public function apply(UserAttendanceDetailRequest $request)
    {
        $work = Work::findOrFail($request->work_id);

        $targetUserId = $work->user_id;

        $now = CarbonImmutable::now();
        $date = $now->toDateString();

        $work->update([
            'attendance' => $request->attendance,
            'leaving' => $request->leaving,
            'remarks' => $request->remarks,
            'update' => 'done',
            'application_date' => $date,
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

                BreakTime::find($break_id)->update([
                    'start' => $start,
                    'stop' => $stop,
                    'update' => 'done',
                    'application_date' => $now,
                ]);
            }
        }

        return redirect('/admin/attendance/detail/' . $work->id);
    }
}
