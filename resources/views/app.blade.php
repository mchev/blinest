<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" <?php if(Route::is('faq')) : ?>itemscope itemtype="https://schema.org/FAQPage"<?php endif; ?>>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title inertia>{{ config('app.name', 'Blinest') }}</title>
        <meta inertia name="description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc.">
        <link rel="canonical" href="{{ URL::current() }}" />
        <link rel="icon" type="image/x-icon" href="/favicon.svg">

        @production
            <script async defer data-website-id="f330893a-1490-47c0-8e3f-2e0fb4a88818" src="https://analytics.pegase.io/pegasestats.js"></script>
        @endproduction
        
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @routes
        @inertiaHead
    
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "url": "{{ config('app.url') }}",
            "logo": "https://blinest.com/images/statics/logo_blinest.png"
        }
        </script>

        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "url": "{{ config('app.url') }}",
          "potentialAction": [{
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "{{ config('app.url') }}?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
          }]
        }
        </script>
</head>
<body class="font-sans antialiased bg-neutral-900">
    @inertia
</body>
</html>
