<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" <?php if(Route::is('faq')) : ?>itemscope itemtype="https://schema.org/FAQPage"<?php endif; ?>>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">        
        <link rel="canonical" href="{{ url()->current() }}" />
        <link rel="icon" type="image/x-icon" href="/favicon.svg">

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
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @routes()
        @inertiaHead
</head>
<body class="font-sans antialiased bg-[#0F172A]">
    @inertia
</body>
</html>