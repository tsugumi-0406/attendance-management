<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea_market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <img src="{{ asset('storage/COACHTECHヘッダーロゴ.png') }}" alt="アプリロゴ" class="header__logo">
        <div class="header-inner">
            <a class="header-inner__link" href="/admin/attendance/list">勤怠一覧</a>
            <a class="header-inner__link" href="/admin/staff/list">スタッフ一覧</a>
            <a class="header-inner__link" href="/stamp_correction_request/list">申請一覧</a>
            <form action="/admin/logout" method="post">
                @csrf
                <button class="header-inner__logout" type="submit">ログアウト</button>
            </form>
        </div>       
    </header>
    
    <main class="main">
        @yield('content')
    </main>
</body>

</html>