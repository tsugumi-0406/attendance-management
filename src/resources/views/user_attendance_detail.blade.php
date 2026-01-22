@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user_attendance_detail.css') }}" />
@endsection

@section('content')
    <div class="main__inner">
        <div class="main-title">
            <div class="marin-title__div">a</div>
            <h1 class="main-title__sentence"> 勤怠詳細</h1>
        </div>
        <form action="/attendance/correction/apply" class="form" method="post">
            @csrf
            <div class="form-inner">
                <div class="form-line">
                    <p class="form-item">名前</p>
                    <p class="form-data__name">{{ $user->name }}</p>
                </div>
                <div class="form-line">
                    @php
                        $work_day = Carbon\Carbon::parse($works->date);
                    @endphp
                    <p class="form-item">日付</p>
                    <p class="form-data__year">{{ $work_day->format('Y'); }}年</p>
                    <p class="form-data__date">{{ $work_day->format('n'); }}月{{ $work_day->format('j'); }}日</p>
                </div>
                <div class="form-line">
                    @php
                        $work_id = $works->id;

                        $work_attendance = Carbon\Carbon::parse($works->attendance)->format('H:i');

                        if($works->leaving == null){
                            $work_leaving = '--:--';
                        }else{
                            $work_leaving = Carbon\Carbon::parse($works->leaving)->format('H:i');
                        }

                        if($works->remarks == null){
                            $works_remarks = '';
                        }else{
                            $works_remarks = $works->remarks;
                        }
                    @endphp
                    <input type="text" hidden value="{{ $work_id }}" name="work_id">
                    <p class="form-item">出勤・退勤</p>
                    <input type="text" class="form-data__attendance" value="{{ $work_attendance }}" name="attendance">
                    <p class="form-data__mark">～</p>
                    <input type="text" class="form-data__leaving" value="{{ $work_leaving }}" name="leaving">
                </div>
                @foreach($breaks as $break)
                    <div class="form-line">
                        @php
                            $break_start = Carbon\Carbon::parse($break->start)->format('H:i');

                            if($break->stop == null){
                                $break_stop = '--:--';
                            }else{
                                $break_stop = Carbon\Carbon::parse($break->stop)->format('H:i');
                            }

                            if($break->remarks == null){
                                $break_remarks = '';
                            }else{
                                $break_remarks = $break->remarks;
                            }
                        @endphp
                        <p class="form-item">休憩</p>
                        <input type="text" class="form-data__break-start" value="{{ $break_start }}" name="start">
                        <p class="form-data__mark">～</p>
                        <input type="text" class="form-data__break-end" value="{{ $break_stop }}" name="stop">
                    </div>
                @endforeach
                <div class="form-line">
                    <p class="form-item">備考</p>
                    <textarea class="form-data__remarks" name="remarks">
                        {{ $works_remarks }}{{ $break_remarks }}
                    </textarea>
                </div>
            </div>
            <input type="submit" value="修正" class="form-button">
        </form>
    </div>
@endsection
