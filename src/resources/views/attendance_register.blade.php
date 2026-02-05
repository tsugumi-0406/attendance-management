<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea_market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/attendance_register.css') }}" />
</head>

<body>
    <header class="header">
        <img src="{{ asset('storage/COACHTECHヘッダーロゴ.png') }}" alt="アプリロゴ" class="header__logo">
        <div class="header-inner">
            @if($status === 'finished')
                <a class="header-inner__link" href="/attendance/list">今月の出勤一覧</a>
                <a class="header-inner__link" href="/stamp_correction_request/list">申請一覧</a>
            @else
                <a class="header-inner__link" href="/attendance">勤怠</a>
                <a class="header-inner__link" href="/attendance/list">勤怠一覧</a>
                <a class="header-inner__link" href="/stamp_correction_request/list">申請</a>
            @endif
            <form action="/logout" method="post">
                @csrf
                <button class="header-inner__logout" type="submit">ログアウト</button>
            </form>
        </div>       
    </header>

    <main class="main">
        @if($status === 'finished')
            <p class="main__situation">退勤済</p>

        @elseif ($status === 'breaking') 
            <p class="main__situation">休憩中</p>

        @elseif ($status === 'working') 
            <p class="main__situation">出勤中</p>

        @elseif ($status === 'off')
            <p class="main__situation">勤務外</p>

        @endif


        <p class="main__date">{{ $now->year; }}年{{ $now->month; }}月{{ $now->day; }}日</p>
        <p class="main__time" id="realtime-clock">{{ $now->format('H:i') }}</p>


        @if($status === 'finished')
            <p class="main__situation-finished">お疲れ様でした。</p>

        @elseif ($status === 'breaking')
            <form action="/stamp/break/stop" method="post" class="main-form">
                @csrf
                <button class="main-form__button-breake">休憩戻</button>
            </form>

        @elseif ($status === 'working')
            <div class="working">
                <form action="/stamp/leave" method="post" class="main-form">
                    @csrf
                    <button class="main-form__button">退勤</button>
                </form>
                <form action="/stamp/break/start" method="post" class="main-form">
                    @csrf
                    <button class="main-form__button-breake">休憩入</button>
                </form>
            </div>

        @elseif ($status === 'off')
            <!-- 出勤ボタン -->
            <form action="/stamp/attendance" method="post" class="main-form">
                @csrf
                <button class="main-form__button">出勤</button>
            </form>
        @endif
    </main>


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
</body>

</html>