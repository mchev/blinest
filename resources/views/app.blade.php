<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" <?php if(Route::is('faq')) : ?>itemscope itemtype="https://schema.org/FAQPage"<?php endif; ?>>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">        
        <link rel="canonical" href="{{ url()->current() }}" />
        <link rel="icon" type="image/x-icon" href="/favicon.svg">

        @production
            <script async defer data-website-id="f330893a-1490-47c0-8e3f-2e0fb4a88818" src="https://analytics.pegase.io/pegasestats.js"></script>
        @endproduction
        
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @routes
        @inertiaHead
</head>
<body class="font-sans antialiased bg-neutral-900">
    @inertia
</body>
</html>
