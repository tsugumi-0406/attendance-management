@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance_register.css') }}" />
@endsection

@section('content')
<!-- @if($status === 'finished')
    <p class="main__situation">退勤済</p>

@elseif ($status === 'breaking') 
    <p class="main__situation">休憩中</p>

@elseif ($status === 'working') 
    <p class="main__situation">出勤中</p>

@elseif ($status === 'off')
    <p class="main__situation">勤務外</p>

@endif -->


<p class="main__date">{{ $now->year; }}年{{ $now->month; }}月{{ $now->day; }}日</p>
<p class="main__time" id="realtime-clock">{{ $now->format('H:i') }}</p>



<form action="/stamp/attendance" method="post" class="main-form">
    @csrf
    <!-- <input type="hidden" name="date" value="$date">
    <input type="hidden" name="attendance" value="$time"> -->
    <button class="main-form__button">出勤</button>
</form>

<script>
    function updateClock() {
        var date = new Date();
        // 表示形式をカスタマイズできます (例: 'ja-JP'で日本表記)
        var timeString = date.toLocaleTimeString('ja-JP', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
        document.getElementById('realtime-clock').innerHTML = timeString;
    }

    // 1秒ごとに updateClock 関数を実行
    setInterval(updateClock, 1000);
    // 初回実行
    updateClock();
</script>
@endsection