@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance_register.css') }}" />
@endsection

@section('content')
<p class="main__situation">勤務外</p>

<p class="main__date">{{ now()->locale('ja')->isoFormat('YYYY年MM月DD日（ddd）') }}</p>
<p class="main__time" id="realtime-clock">{{ now()->format('H:i') }}</p>



<form action="" class="main-form">
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