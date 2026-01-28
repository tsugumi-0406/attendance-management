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
}
