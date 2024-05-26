<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1024,user-scalable=no">
    <title inertia>app</title>

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

    <!-- meta -->
    <meta property="og:title" content="리딩수학" />
    <meta property="og:url" content="https://test2.readingmath.co.kr/app" />
    <meta property="og:image" content="/og_image.jpg" />
    <meta property="og:description" content="리딩수학 학습프로그램" />

    <!-- PWA용 manifest, color-->
    <link rel="manifest" href="/manifest.webmanifest" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    @routes('app')
    @vite(['resources/app/app.ts', "resources/app/pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script type="text/javascript" src="https://tbnpg.settlebank.co.kr/resources/js/v1/SettlePG_v1.2.js"></script>
</body>

</html>
