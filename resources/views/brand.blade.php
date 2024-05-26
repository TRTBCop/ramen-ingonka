<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-title" content="매일국어">
    <meta name="description" content="1등급의 기적의 공부법, 매일국어1">
    <meta property="og:title" content="매일국어">
    <meta property="og:url" content="https://www.dailykor.com/">
    <meta property="og:image" content="https://www.dailykor.com/img/brand/og_meta.png">
    <meta property="og:description" content="1등급의 기적의 공부법, 매일국어! 개별 맞춤 진단을 바탕으로 자기주도훈련을 통해 혼자서도 공부를 잘하는 방법을 가르칩니다.">


    <title inertia>리딩수학</title>

    <!-- Scripts -->
    @routes
    @vite(['resources/brand/app.ts', "resources/brand/pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body>

    @inertia

</body>

</html>
