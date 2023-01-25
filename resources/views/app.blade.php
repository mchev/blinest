<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title inertia>{{ config('app.name', 'Blinest') }}</title>
        <meta inertia name="description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc.">
        <link rel="canonical" href="{{ URL::current() }}" />
        <link rel="icon" type="image/x-icon" href="/favicon.svg">

        <meta property="og:locale" content="fr_FR"/>
        <meta property="og:locale:alternate" content="en_US"/>

        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "url": "https://blinest.com",
            "logo": "https://blinest.com/images/statics/logo_blinest.png"
        }
        </script>


        @production
            <script async defer data-website-id="f330893a-1490-47c0-8e3f-2e0fb4a88818" src="https://analytics.pegase.io/pegasestats.js"></script>
        @endproduction
        
        @vite('resources/js/app.js')
        @routes
    
</head>
<body class="font-sans antialiased bg-neutral-900">
    @inertia
</body>
</html>
