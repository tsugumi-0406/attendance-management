@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/administrator_attendance_list.css') }}" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
@endsection

@section('content')
    <div class="main__inner">
        <div class="main-title">
            <div class="marin-title__div">a</div>
            <h1 class="main-title__sentence"> {{ $base_date->format('Y'); }}年{{ $base_date->format('n'); }}月{{ $base_date->format('d'); }}日の勤怠</h1>
        </div>
        <div class="day">
            <div class="day-before">
                <a href="/admin/attendance/list?day={{ $link_day_before }}" class="day-before__link">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                    前日
                </a>
            </div>
            <div class="day-now">
                <ion-icon name="calendar-outline"></ion-icon>
                {{ $base_date->format('Y/m/d'); }}
            </div>
            <div class="day-after">
                <a href="/admin/attendance/list?day={{ $link_day_after }}" class="day-after__link">
                    翌日
                    <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
            </div>
        </div>

        <table class="table">
            <tr>
                <th class="th">名前</th>
                <th class="th">出勤</th>
                <th class="th">退勤</th>
                <th class="th">休憩</th>
                <th class="th">合計</th>
                <th class="th">詳細</th>
            </tr>
            @foreach ($works as $work) 
                <tr>
                    <td class="td">{{ $work->user->name }}</td>
                    <td class="td">{{ Carbon\Carbon::parse($work->attendance)->format('H:i'); }}</td>
                    <td class="td">
                        @if($work->leaving == null)
                            {{ '--:--' }}
                        @else
                            {{ Carbon\Carbon::parse($work->leaving)->format('H:i'); }}
                        @endif
                    </td>
                    @php
                        $break_seconds = $break_seconds_by_user[$work->user_id] ?? 0;
                    @endphp
                    <td class="td">
                        {{ floor($break_seconds / 3600) . ':' . sprintf('%02d', floor(($break_seconds % 3600) / 60)) }}
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
                    <td class="td"><a class="td-detail" href="{{ route('admin.attendance.detail', ['id' => $work->id]) }}" class="item-link"><span class="td-detail">詳細</span></a></td>
                </tr>
            @endforeach
        </table>
    </div>

@endsection