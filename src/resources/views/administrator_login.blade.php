<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea_market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/administrator_login.css') }}">
</head>

<body>
    <header class="header">
        <img src="{{ asset('storage/COACHTECHヘッダーロゴ.png') }}" alt="アプリロゴ" class="header__logo">
    </header>

    <main>
        <div class="main-inner">
            <h1 class="main-title">
                管理者ログイン
            </h1>
            <form action="/login" class="main-form" method="post" novalidate>
            @csrf
                <label for="" class="main-form__label">メールアドレス</label>
                <input type="text" class="main-form__input" name="email">
                <div class="main-form__error">
                    @error('email')
                        {{ $errors->first('email') }}
                    @enderror
                </div>
                <label for="" class="main-form__label"> パスワード</label>
                <input type="password" class="main-form__input" name="password">
                <div class="main-form__error">
                    @error('password')
                        {{ $errors->first('password') }}
                    @enderror
                </div>
                <input type="submit" class="main-dorm__submit" value="管理者ログインする">
            </form>
        </div>
    </main>
</body>

</html> 