@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/approval.css') }}" />
@endsection


@section('content')
    <div class="main__inner">
        <div class="main-title">
            <div class="marin-title__div">a</div>
            <h1 class="main-title__sentence"> 勤怠詳細</h1>
        </div>


        <form action="/admin/approve" class="form" method="post">
            @csrf
            <div class="form-inner">
                <div class="form-line">
                    <p class="form-item">名前</p>
                    <p class="form-data__name">{{ $work->user->name }}</p>
                </div>


                <div class="form-line">
                    @php
                        $work_day = Carbon\Carbon::parse($work->date);
                    @endphp
                    <p class="form-item">日付</p>
                    <p class="form-data__year">{{ $work_day->format('Y'); }}年</p>
                    <p class="form-data__date">{{ $work_day->format('n'); }}月{{ $work_day->format('j'); }}日</p>
                </div>


                <div class="form-line">
                    @php
                        $work_status = $work->update;

                        $break_request = [];
                    @endphp

                    @if($work_status == 'pending')
                        @php
                            $work_id = $work->id;
                            $unapproved_work_attendance = Carbon\Carbon::parse($unapproved_work->attendance)->format('H:i');

                            if($unapproved_work->leaving == null){
                                $unapproved_work_leaving = '--:--';
                            }else{
                                $unapproved_work_leaving = Carbon\Carbon::parse($unapproved_work->leaving)->format('H:i');
                            }

                            if($unapproved_work->remarks == null){
                                $unapproved_work_remarks = '';
                            }else{
                                $unapproved_work_remarks = $unapproved_work->remarks;
                            }
                        @endphp
                        <input type="hidden" value="{{ $work_id }}" name="work_id">
                        <p class="form-item">出勤・退勤</p>
                        <p class="form-data__attendance">{{ $unapproved_work_attendance }}</p>
                        <p class="form-data__mark">～</p>
                        <p class="form-data__leaving">{{ $unapproved_work_leaving }}</p>
                    @else
                        @php
                            $work_attendance = Carbon\Carbon::parse($work->attendance)->format('H:i');

                            if($work->leaving == null){
                                $work_leaving = '--:--';
                            }else{
                                $work_leaving = Carbon\Carbon::parse($work->leaving)->format('H:i');
                            }

                            if($work->remarks == null){
                                $work_remarks = '';
                            }else{
                                $work_remarks = $work->remarks;
                            }
                        @endphp
                        <p class="form-item">出勤・退勤</p>
                        <p class="form-data__attendance">{{ $work_attendance }}</p>
                        <p class="form-data__mark">～</p>
                        <p class="form-data__leaving">{{ $work_leaving }}</p>
                    @endif
                </div>


                @if($work_status == 'pending')
                    @foreach($unapproved_breaks as $index => $unapproved_break)
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
                            <input type="hidden" value="{{ $unapproved_break_id }}" name="unapproved_break_requests[{{ $index }}][unapproved_break_id]" readonly>
                            <p class="form-item">
                                休憩{{ $loop->iteration === 1 ? '' : $loop->iteration }}
                            </p>
                            <p class="form-data__break-start">{{ $unapproved_break_start }}</p>
                            <p class="form-data__mark">～</p>
                            <p class="form-data__break-end">{{ $unapproved_break_stop }}</p>
                        </div>
                    @endforeach 
                @else
                    @foreach($breaks as $break)
                        <div class="form-line">
                            @php
                                $break_start = Carbon\Carbon::parse($break->start)->format('H:i');

                                if($break->stop == null){
                                    $break_stop = '--:--';
                                }else{
                                    $break_stop = Carbon\Carbon::parse($break->stop)->format('H:i');
                                }    
                            @endphp
                            <p class="form-item">
                                休憩{{ $loop->iteration === 1 ? '' : $loop->iteration }}
                            </p>
                            <p class="form-data__break-start">{{ $break_start }}</p>
                            <p class="form-data__mark">～</p>
                            <p class="form-data__break-end">{{ $break_stop }}</p>
                        </div>
                    @endforeach
                @endif
                        


                <div class="form-line">
                    <p class="form-item">備考</p>
                    @if($work_status == 'pending')
                        <p class="form-data__remarks">
                            {{ $unapproved_work_remarks }}
                        </textarea>
                     @else
                        <p class="form-data__remarks">
                            {{ $work_remarks }}
                        </p>
                    @endif
                </div>
            </div>

            @if($work_status == 'pending')
                <input type="submit" value="承認" class="form-button">
            @else
                <p class="pending-comment">承認済み</p>
            @endif
        </form>
    </div>
@endsection
