<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}" />
</head>
<body>
    <div class="layout">
        <div>
            <h1>メールアドレスを確認してください</h1>
            <p>登録時に入力したメールアドレスに確認リンクを送信しました。メール内のリンクをクリックして認証を完了してください。</p>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit">確認メールを再送する</button>
            </form>
        </div>
    </div>
</body>
</html>