@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/administrator_attendance_detail.css') }}" />
@endsection

@section('content')
    <div class="main__inner">

        <div class="main-title">
            <div class="marin-title__div">a</div>
            <h1 class="main-title__sentence"> 勤怠詳細</h1>
        </div>


        <form action="/admin/attendance/correction/apply" class="form" method="post">
            @csrf
            <div class="form-inner">
                <div class="form-line">
                    <p class="form-item">名前</p>
                    <p class="form-data__name">{{ $user->name }}</p>
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


                    
                    @php
                        $work_id = $work->id;

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
                    <input type="text" hidden value="{{ $work_id }}" name="work_id">
                    <p class="form-item">出勤・退勤</p>
                    <input type="text" class="form-data__attendance" value="{{ $work_attendance }}" name="attendance">
                    <p class="form-data__mark">～</p>
                    <input type="text" class="form-data__leaving" value="{{ $work_leaving }}" name="leaving">
                </div>


                
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


                <div class="form-line">
                    <p class="form-item">備考</p>
                    <textarea class="form-data__remarks" name="remarks">{{ $work_remarks }}</textarea>
                </div>
            </div>

            <div class="error">
                <br>
                    @error('attendance')
                        {{ $errors->first('attendance') }}
                    @enderror
                <br>
                    @error('leaving')
                        {{ $errors->first('leaving') }}
                    @enderror
                <br>
                    @error('break_requests')
                        {{ $errors->first('break_requests') }}
                    @enderror
                <br>
                    @if ($errors->has('break_requests.*.start') || $errors->has('break_requests.*.stop'))
                        @foreach ($errors->get('break_requests.*.start') as $messages)
                            @foreach ($messages as $message)
                                <div>{{ $message }}</div>
                            @endforeach
                        @endforeach

                        @foreach ($errors->get('break_requests.*.stop') as $messages)
                            @foreach ($messages as $message)
                                <div>{{ $message }}</div>
                            @endforeach
                        @endforeach
                    @endif
                <br>
                    @error('remarks')
                        {{ $errors->first('remarks') }}
                    @enderror
            </div>
            <input type="submit" value="修正" class="form-button">
        </form>
    </div>
@endsection

