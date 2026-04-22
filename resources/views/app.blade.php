<!DOCTYPE html>
@php
    $s = \App\Models\Setting::current();
    $pageTitle      = $s->meta_title ?: $s->site_name;
    $metaDesc       = $s->meta_description ?: '';
    $metaKeywords   = $s->meta_keywords ?: '';
    $ogImage        = $s->meta_og_image  ? \Illuminate\Support\Facades\Storage::url($s->meta_og_image)  : null;
    $faviconUrl     = $s->site_favicon   ? \Illuminate\Support\Facades\Storage::url($s->site_favicon)   : null;
    $ogType         = $s->meta_og_type ?: 'website';
    // Use APP_PUBLIC_URL for canonical/OG links (production domain) so they
    // are always correct regardless of the local APP_URL.
    $canonicalUrl   = rtrim(env('APP_PUBLIC_URL', config('app.url')), '/');
    $gaId           = $s->google_analytics_id;
    $gtmId          = $s->google_tag_manager_id;
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- ── Favicon ─────────────────────────────────────────────── --}}
        @if($faviconUrl)
            @php $faviconExt = pathinfo($s->site_favicon, PATHINFO_EXTENSION); @endphp
            @if($faviconExt === 'svg')
                <link rel="icon" href="{{ $faviconUrl }}" type="image/svg+xml">
            @elseif($faviconExt === 'png')
                <link rel="icon" href="{{ $faviconUrl }}" type="image/png">
            @else
                <link rel="icon" href="{{ $faviconUrl }}" sizes="any">
            @endif
            <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
        @else
            <link rel="icon" href="/favicon.ico" sizes="any">
            <link rel="icon" href="/favicon.svg" type="image/svg+xml">
            <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @endif

        {{-- ── Primary SEO ─────────────────────────────────────────── --}}
        <title>{{ $pageTitle }}</title>
        @if($metaDesc)
            <meta name="description" content="{{ $metaDesc }}">
        @endif
        @if($metaKeywords)
            <meta name="keywords" content="{{ $metaKeywords }}">
        @endif
        <link rel="canonical" href="{{ $canonicalUrl }}">

        {{-- ── Open Graph ──────────────────────────────────────────── --}}
        <meta property="og:type"        content="{{ $ogType }}">
        <meta property="og:url"         content="{{ $canonicalUrl }}">
        <meta property="og:title"       content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $metaDesc }}">
        @if($ogImage)
            <meta property="og:image"       content="{{ $ogImage }}">
            <meta property="og:image:width"  content="1200">
            <meta property="og:image:height" content="630">
        @endif
        <meta property="og:site_name"   content="{{ $s->site_name }}">

        {{-- ── Twitter Card ────────────────────────────────────────── --}}
        <meta name="twitter:card"        content="{{ $ogImage ? 'summary_large_image' : 'summary' }}">
        <meta name="twitter:title"       content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $metaDesc }}">
        @if($ogImage)
            <meta name="twitter:image" content="{{ $ogImage }}">
        @endif

        {{-- ── Google Tag Manager (head) ───────────────────────────── --}}
        @if($gtmId)
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
        @endif

        {{-- ── Google Analytics 4 ─────────────────────────────────── --}}
        @if($gaId)
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $gaId }}');
            </script>
        @endif

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

        {{-- ── Google Tag Manager (body) ───────────────────────────── --}}
        @if($gtmId)
            <noscript>
                <iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
                        height="0" width="0" style="display:none;visibility:hidden"></iframe>
            </noscript>
        @endif

        <div id="app"></div>
    </body>
</html>
