<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">        
        <link rel="canonical" href="{{ url()->current() }}" />
        <link rel="icon" type="image/x-icon" href="/favicon.svg">
        <link rel="manifest" href="/manifest.json">
        
        <!-- PWA / Standalone Mode -->
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Blinest">
        <meta name="theme-color" content="#0F172A">
        <meta name="msapplication-TileColor" content="#0F172A">
        <meta name="msapplication-navbutton-color" content="#0F172A">
        <meta name="application-name" content="Blinest">
        
        <!-- Prevent address bar from showing on mobile -->
        <meta name="format-detection" content="telephone=no">

        @production
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-4YLB1LND9B"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-4YLB1LND9B');
            </script>

            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6495635642797272" crossorigin="anonymous"></script>
        @endproduction
        
        <script src="https://cdn.jsdelivr.net/npm/lamejs@1.2.1/lame.all.js"></script>
        
        @if(isset($page['props']['structured_data']) && $page['props']['structured_data'])
        <script type="application/ld+json">
        {!! json_encode($page['props']['structured_data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
        @endif
        
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @routes()
        @inertiaHead
</head>
<body class="font-sans antialiased bg-[#0F172A]">
    @inertia
</body>
</html>