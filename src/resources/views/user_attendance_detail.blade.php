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
            @if (request()->is('admin/*'))
                <input type="hidden" name="from" value="admin">
            @endif


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
                        $work_status = $works->update;

                        $break_request = [];
                    @endphp


                    @if($work_status == 'pending')
                        @php
                            $unapproved_work_attendance = Carbon\Carbon::parse($unapproved_works->attendance)->format('H:i');

                            if($unapproved_works->leaving == null){
                                $unapproved_work_leaving = '--:--';
                            }else{
                                $unapproved_work_leaving = Carbon\Carbon::parse($unapproved_works->leaving)->format('H:i');
                            }

                            if($unapproved_works->remarks == null){
                                $unapproved_works_remarks = '';
                            }else{
                                $unapproved_works_remarks = $unapproved_works->remarks;
                            }
                        @endphp
                        <p class="form-item">出勤・退勤</p>
                        <p class="form-data__attendance-pending">{{ $unapproved_work_attendance }}</p>
                        <p class="form-data__mark">～</p>
                        <p class="form-data__leaving-pending">{{ $unapproved_work_leaving }}</p>
                    @else
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
                    @endif
                </div>


                @if($work_status == 'pending')
                    @foreach($unapproved_breaks as $unapproved_break)
                        <div class="form-line">
                            @php
                                $unapproved_break_id = $unapproved_break->id;

                                $unapproved_break_start = Carbon\Carbon::parse($unapproved_break->start)->format('H:i');

                                if($unapproved_break->stop == null){
                                    $unapproved_break_stop = '--:--';
                                }else{
                                    $unapproved_break_stop = Carbon\Carbon::parse($unapproved_break->stop)->format('H:i');
                                }    
                            @endphp
                            <p class="form-item">休憩</p>
                            <p class="form-data__break-start-pending">{{ $unapproved_break_start }}</p>
                            <p class="form-data__mark">～</p>
                            <p class="form-data__break-end-pending">{{ $unapproved_break_stop }}</p>
                        </div>
                    @endforeach       
                @else
                    @foreach($breaks as $index => $break)
                        <div class="form-line">
                            @php
                                $break_id = $break->id;

                                $break_start = Carbon\Carbon::parse($break->start)->format('H:i');

                                if($break->stop == null){
                                    $break_stop = '--:--';
                                }else{
                                    $break_stop = Carbon\Carbon::parse($break->stop)->format('H:i');
                                }    
                            @endphp
                            <input type="text" hidden value="{{ $break_id }}" name="break_requests[{{ $index }}][break_id]">
                            <p class="form-item">休憩</p>
                            <input type="text" class="form-data__break-start" value="{{ $break_start }}" name="break_requests[{{ $index }}][start]">
                            <p class="form-data__mark">～</p>
                            <input type="text" class="form-data__break-end" value="{{ $break_stop }}" name="break_requests[{{ $index }}][stop]">
                        </div>
                    @endforeach
                @endif


                <div class="form-line">
                    <p class="form-item">備考</p>
                    @if($work_status == 'pending')
                        <p class="form-data__remarks-pending" name="remarks">
                            {{ $unapproved_works_remarks }}
                        </p>
                     @else
                        <textarea class="form-data__remarks" name="remarks">
                            {{ $works_remarks }}
                        </textarea>
                    @endif
                </div>
            </div>


            @if($work_status == 'pending')
                <p class="pending-comment">*承認待ちのため修正はできません。</p>
            @else
                <input type="submit" value="修正" class="form-button">
            @endif


        </form>
    </div>
@endsection
