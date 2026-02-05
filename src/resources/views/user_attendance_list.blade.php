@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user_attendance_list.css') }}" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
@endsection


@section('content')
    <div class="main__inner">
        <div class="main-title">
            <div class="main-title__div">a</div>
            <h1 class="main-title__sentence"> 勤怠一覧</h1>
        </div>
        <div class="month">
            <div class="month-before">
                <a href="/attendance/list?month={{ $link_day_before }}" class="month-before__link">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                    前月
                </a>
            </div>
            <div class="month-now">
                <ion-icon name="calendar-outline"></ion-icon>{{ $base_date->format('Y/m'); }}
            </div>
            <div class="month-after">
                <a href="/attendance/list?month={{ $link_day_after }}" class="month-after__link">翌月
                    <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
            </div>
        </div>
        <table class="table">
            <tr>
                <th class="th">日付</th>
                <th class="th">出勤</th>
                <th class="th">退勤</th>
                <th class="th">休憩</th>
                <th class="th">合計</th>
                <th class="th">詳細</th>
            </tr>
            @foreach ($works as $work) 
                <tr>
                    @php
                        $week_dd = Carbon\Carbon::parse($work->date)->dayOfWeek;
                    @endphp
                    <td class="td">
                        {{ Carbon\Carbon::parse($work->date)->format('m/d'); }}
                            ({{ $dd[$week_dd]; }})
                    </td>
                    <td class="td">
                        {{ Carbon\Carbon::parse($work->attendance)->format('H:i'); }}
                    </td>
                    <td class="td">
                        @if($work->leaving == null)
                            {{ '--:--' }}
                        @else
                            {{ Carbon\Carbon::parse($work->leaving)->format('H:i'); }}
                        @endif
                    </td>
                    @php
                        $work_date = Carbon\Carbon::parse($work->date)->format('Y-m-d');
                        if(empty( $break_date[ $work_date ])){
                            $break_seconds = 0;
                        }
                        else{
                            $break_seconds = $break_date[$work_date];
                        }
                    @endphp
                    <td class="td">
                        {{ floor($break_seconds/ 3600) . ':' . sprintf('%02d',floor(($break_seconds % 3600) / 60)); }}
                    </td>
                    <td class="td">
                        @if(empty( $work['leaving']))
                            {{ '--' . ':' . '--'}}
                        @else
                            @php
                                $start_date_time = $work['date'] . ' ' . $work['attendance'];
                                $stop_date_time = $work['date'] . ' ' . $work['leaving']; 
                                $start_time = Carbon\Carbon::parse($start_date_time);
                                $end_time = Carbon\Carbon::parse($stop_date_time); 
                                $work_seconds = $start_time->diffInSeconds($end_time) - $break_seconds;
                            @endphp
                            {{ floor($work_seconds/ 3600) . ':' . sprintf('%02d',floor(($work_seconds % 3600) / 60)); }}
                            
                        @endif
                    </td>
                    <td class="td">
                        <a class="td-detail" href="{{ route('attendance.detail', ['id' => $work->id]) }}" class="item-link">
                            詳細
                        </a>
                    </td>
                </tr>
            @endforeach
            
        </table>
    </div>
@endsection