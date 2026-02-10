<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\BreakTime;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Date;

class StaffAttendanceListController extends Controller
{
    // スタッフ別勤怠一覧画面（管理者）を表示する
    public function list(Request $request, $id)
    {
        $user = User::where('id', $id)
                ->first();

        $now = CarbonImmutable::now();

        if($request->query('month') === null)
            $year_month = $now->format('Y-m');
        else{
            $year_month = $request->query('month');
        }

        // 基準月の設定
        $list_day = $year_month . "-01";

        // 翌月・先月用のリンク用の日付作成
        $link_day_before = Carbon::parse($list_day)->subMonth()->format('Y-m');
        $link_day_after = Carbon::parse($list_day)->addMonth()->format('Y-m');

        $base_date = Carbon::parse($list_day);
        $base_date_next = Carbon::parse($list_day);
        $next_date = $base_date_next->addMonth()->subDay();
        
        // 表示する日付の範囲用の値の設定
        $firstDateOfMonth = $base_date;  
        $lastDateOfMonth = $next_date;

        $works = Work::where('user_id', $user->id)
                ->whereBetween('date', [$firstDateOfMonth, $lastDateOfMonth])
                ->get();

        $dd = array(
            '0' => '日',
            '1' => '月',
            '2' => '火',
            '3' => '水',
            '4' => '木',
            '5' => '金',
            '6' => '土'
            );

        $breaks = BreakTime::where('user_id', $user->id)
            ->whereBetween('date', [$firstDateOfMonth, $lastDateOfMonth])
            ->get();

        $break_date = [];
        foreach($breaks as $break){
            $date = $break['date'];

            if(empty( $break_date[ $date ] ))
                $break_date[$date] = 0;

            // 休憩終了がない場合   
            if($break['stop'] == null){}
            else{
                $start_date_time = $date . ' ' . $break['start'];
                $stop_date_time = $date . ' ' . $break['stop']; 
                $start_time = Carbon::parse($start_date_time);
                $end_time = Carbon::parse($stop_date_time); 
                $diffInSeconds = $start_time->diffInSeconds($end_time);

                $break_date[$date] += $diffInSeconds;   
            }
        }
  
        return view('staff_attendance_list', compact('works', 'breaks', 'dd', 'user', 'base_date', 'link_day_before', 'link_day_after', 'break_date'));
    }

    // 該当ユーザーの出勤記録をCSV出力する
    public function export(Request $request, $id)
    {
        $user_id = $id;

        $year_month = $request->query('month', now()->format('Y-m'));
        $base = Carbon::createFromFormat('Y-m', $year_month);
        $list_day = $year_month . "-01";
        $base_date = Carbon::parse($list_day);
        $base_date_next = Carbon::parse($list_day);
        $next_date = $base_date_next->addMonth()->subDay();
        $firstDateOfMonth = $base->copy()->startOfMonth()->startOfDay();
        $lastDateOfMonth  = $base->copy()->endOfMonth()->endOfDay();

        $works = Work::where('user_id', $user_id)
            ->whereBetween('date', [$firstDateOfMonth, $lastDateOfMonth])
            ->get();

        $breaks = BreakTime::where('user_id', $user_id)
            ->whereBetween('date', [$firstDateOfMonth, $lastDateOfMonth])
            ->get();
        
        $break_date = [];
        foreach($breaks as $break){
            $date = $break['date'];

            if(empty( $break_date[ $date ] ))
                $break_date[$date] = 0;

            // 休憩終了がない場合   
            if($break['stop'] == null)
                {}
            else{
                $start_date_time = $date . ' ' . $break['start'];
                $stop_date_time = $date . ' ' . $break['stop']; 
                $start_time = Carbon::parse($start_date_time);
                $end_time = Carbon::parse($stop_date_time); 
                $diffInSeconds = $start_time->diffInSeconds($end_time);

                $break_date[$date] += $diffInSeconds;   
            }
        }

        $row = [];
        $rows = [];
        foreach($works as $work){
            $date = $work['date'];

            $row['date'] = $work->date;

            $row['attendance'] = $work->attendance;

            $row['leaving'] = $work->leaving;

            if(empty( $break_date[ $date ])){
                $break_seconds = 0;
            }else{
                $break_seconds = $break_date[$date];
            }

            $row['break_time'] = floor($break_seconds/ 3600) . ':' . sprintf('%02d',floor(($break_seconds % 3600) / 60));

            if(empty( $work['leaving'])){
                $row['work_time'] = '--:--';
            }else{
                $attendance_time_string = $work['date'] . ' ' . $work['attendance'];
                $leaving_time_string = $work['date'] . ' ' . $work['leaving']; 
                $attendance_time = Carbon::parse($attendance_time_string);
                $leaving_time = Carbon::parse($leaving_time_string); 

                $work_seconds = $attendance_time->diffInSeconds($leaving_time) - $break_seconds;

                $row['work_time'] = floor($work_seconds/ 3600) . ':' . sprintf('%02d',floor(($work_seconds % 3600) / 60));
            }

            $row['created_at'] = $work->created_at;

            $row['updated_at'] = $work->updated_at;

            $rows[] = $row;
        }
        
        $csvHeader = [
            'date', 'attendance', 'leaving', 'break_time', 'work_time', 'created_at', 'updated_at'
        ];

        $response = new StreamedResponse(function () use ($csvHeader, $rows) {
            $createCsvFile = fopen('php://output', 'w');

            mb_convert_variables('SJIS-win', 'UTF-8', $csvHeader);

            fputcsv($createCsvFile, $csvHeader);

            foreach ($rows as $row) {
                $row['created_at'] = Date::make($row['created_at'])->setTimezone('Asia/Tokyo')->format('Y/m/d H:i:s');
                $row['updated_at'] = Date::make($row['updated_at'])->setTimezone('Asia/Tokyo')->format('Y/m/d H:i:s');

                $row_correct = array($row['date'], $row['attendance'], $row['leaving'], $row['break_time'], $row['work_time'], $row['created_at'], $row['updated_at']);

                fputcsv($createCsvFile, $row_correct);
            }

            fclose($createCsvFile);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendance.csv"',
        ]);

        return $response;
    }
}
