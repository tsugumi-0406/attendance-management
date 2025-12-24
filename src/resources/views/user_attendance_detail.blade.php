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
        <form action="" class="form">
            <div class="form-inner">
                <div class="form-line">
                    <p class="form-item">名前</p>
                    <p class="form-data__name">勤務者名</p>
                </div>
                <div class="form-line">
                    <p class="form-item">日付</p>
                    <p class="form-data__year">勤務年</p>
                    <p class="form-data__date">勤務日付</p>
                </div>
                <div class="form-line">
                    <p class="form-item">出勤・退勤</p>
                    <input type="text" class="form-data__attendance" value=出勤時間></input>
                    <p class="form-data__mark">～</p>
                    <input type="text" class="form-data__leaving" value=退勤時間></input>
                </div>
                <div class="form-line">
                    <p class="form-item">休憩</p>
                    <input type="text" class="form-data__break-start" value=休憩開始時間></input>
                    <p class="form-data__mark">～</p>
                    <input type="text" class="form-data__break-end" value=休憩終了時間></input>
                </div>
                <div class="form-line">
                    <p class="form-item">備考</p>
                    <textarea class="form-data__remarks">修正理由</textarea>
                </div>
            </div>
            <input type="button" value="修正" class="form-button">
        </form>
    </div>
@endsection
