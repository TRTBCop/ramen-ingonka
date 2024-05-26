<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body class="font-sans antialiased">

소셜로그인테스트

<a href="{{ route('auth.social.login', 'naver') }}">네이버</a>

<a href="{{ route('auth.social.login', 'kakao') }}">카카오</a>
</body>

</html>