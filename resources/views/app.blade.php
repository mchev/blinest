<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <meta property="og:image" content="/img/screenshot.jpg" />
        <meta property="og:title" content="Blind-Tests multijoueurs - gratuit et sans inscription" />
        <meta property="og:description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc." />
        <meta property="og:type" content="website" />
        <meta name="twitter:card" content="summary">
        <meta name="twitter:description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc.">
        <meta name="twitter:title" content="Blind-Tests multijoueurs - gratuit et sans inscription">
        <meta name="twitter:site" content="@PegaseMartin">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600;700&display=swap" rel="stylesheet"> 

        @vite('resources/js/app.js')
        @routes
    
</head>
<body class="font-sans antialiased bg-neutral-900">
    @inertia
</body>
</html>
