<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/x-icon" href="/favicon.svg">

        <meta property="og:image" content="/img/screenshot.jpg" />
        <meta property="og:title" content="Blind-Tests multijoueurs" />
        <meta property="og:description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc." />
        <meta property="og:type" content="website" />
        <meta name="twitter:card" content="summary">
        <meta name="twitter:description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc.">
        <meta name="twitter:title" content="Blind-Tests multijoueurs">
        <meta name="twitter:site" content="@PegaseMartin">
 
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
