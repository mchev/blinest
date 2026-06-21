<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @production
            {{-- Ezoic: privacy scripts must load before the header script --}}
            <script data-cfasync="false" src="https://cmp.gatekeeperconsent.com/min.js"></script>
            <script data-cfasync="false" src="https://the.gatekeeperconsent.com/cmp.min.js"></script>
            <script async src="https://www.ezojs.com/ezoic/sa.min.js"></script>
            <script>
                window.ezstandalone = window.ezstandalone || {};
                ezstandalone.cmd = ezstandalone.cmd || [];
            </script>
            <script src="https://ezoicanalytics.com/analytics.js"></script>
        @endproduction

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">        
        <link rel="canonical" href="{{ url()->current() }}" />
        
        <!-- Open Graph / Facebook Meta Tags -->
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Blinest - Quiz musicaux gratuits et multijoueurs" />
        <meta property="og:description" content="Jouez à des quiz musicaux multijoueurs gratuits ! Blind-tests en ligne pour tous les goûts : Années 2000, Disney, Chanson française, Années 80, Rock, Pop, et bien plus encore." />
        <meta property="og:image" content="{{ url('images/statics/screenshot.png') }}" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Blinest - Quiz musicaux multijoueurs" />
        <meta property="og:site_name" content="Blinest" />
        <meta property="og:locale" content="fr_FR" />
        
        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:site" content="@blinest" />
        <meta name="twitter:creator" content="@blinest" />
        <meta property="twitter:domain" content="blinest.com" />
        <meta property="twitter:url" content="{{ url()->current() }}" />
        <meta name="twitter:title" content="Blinest - Quiz musicaux gratuits et multijoueurs" />
        <meta name="twitter:description" content="Jouez à des quiz musicaux multijoueurs gratuits ! Blind-tests en ligne pour tous les goûts : Années 2000, Disney, Chanson française, Années 80, Rock, Pop, et bien plus encore." />
        <meta name="twitter:image" content="{{ url('images/statics/screenshot.png') }}" />
        <meta name="twitter:image:alt" content="Blinest - Quiz musicaux multijoueurs" />
        
        <!-- Favicons -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        
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