<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ganesh K P - Creative Developer Portfolio | 10+ years experience in PHP/Laravel, Vue.js, Python, Flutter, Unity game development">
    <meta name="keywords" content="web developer, software engineer, PHP, Laravel, Vue.js, Python, Flutter, Unity, 3D portfolio">
    <meta name="author" content="Ganesh K P">

    <title>3D Portfolio | Ganesh K P</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#00ffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="GK Portfolio">
    <meta name="application-name" content="GK Portfolio">
    <meta name="msapplication-TileColor" content="#00ffff">
    <meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0a0a0a] text-white overflow-x-hidden">
    <div id="app"></div>
</body>
</html>