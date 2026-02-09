<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/verification.css') }}">
</head>

<body>
    <header class="header">
        <img src="{{ asset('COACHTECHヘッダーロゴ.png') }}" alt="アプリロゴ" class="header__logo">
    </header>

    <main>
        <div class="main-sentence">
            <p class="main-sente__text">
                登録されたメールアドレスに確認メールを送付しました。
                <br>メール認証を完了してください。</br>
            </p>
            <button class="main-form__button" onclick="window.open('https://mailtrap.io/inboxes/4210090/messages/5327534376', '_blank')">認証はこちらから</button>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="main-sentence__link">認証メールを再送する</button>
            </form>
        </div>
    </main>
</body>

</html>