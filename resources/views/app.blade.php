<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @production
            @if ($serveEzoicAds ?? true)
            <script data-cfasync="false" src="https://cmp.gatekeeperconsent.com/min.js"></script>
            <script data-cfasync="false" src="https://the.gatekeeperconsent.com/cmp.min.js"></script>
            <script async src="https://www.ezojs.com/ezoic/sa.min.js"></script>
            <script>
                window.ezstandalone = window.ezstandalone || {};
                ezstandalone.cmd = ezstandalone.cmd || [];
            </script>
            <script src="https://ezoicanalytics.com/analytics.js"></script>
            @endif
        @endproduction

        <meta charset="utf-8">
        @head

        @production
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-4YLB1LND9B"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-4YLB1LND9B');
            </script>
        @endproduction

        <script src="https://cdn.jsdelivr.net/npm/lamejs@1.2.1/lame.all.js"></script>

        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @routes()
        <x-inertia::head />
    </head>
    <body class="font-sans antialiased bg-surface-base">
        <x-inertia::app />
    </body>
</html>
