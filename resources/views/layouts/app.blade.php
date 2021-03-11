<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <script data-ad-client="ca-pub-6495635642797272" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title>@yield('title')</title>
    <link rel="canonical" href="{{ URL::current() }}" />
    <meta name="description" content="@yield('description')">

    <meta name="user-id" content="{{ optional(Auth::user())->id }}">

    <meta property="og:image" content="/img/screenshot.jpg" />
    <meta property="og:title" content="@yield('title')" />
    <meta property="og:description" content="@yield('description')" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS_ID') }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', "{{ env('GOOGLE_ANALYTICS_ID') }}");
    </script>

    <!-- Prefetch -->
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://pagead2.googlesyndication.com">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">

        @include('partials.navbar')

        @yield('content')

        <footer class="footer text-center">
            <div class="container">
              <div class="row">

                <!-- Footer Location -->
                <div class="col-lg-4 mb-5 mb-lg-0">

                </div>

                <!-- Footer Social Icons -->
                <div class="col-lg-4 mb-5 mb-lg-0">
                  <h4 class="text-uppercase mb-4">Partager cette page</h4>
                  <a class="btn btn-outline-light btn-social mx-1" target="_blank" title="Partager sur Facebook" rel="noreferrer" href="https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}">
                    <i class="fab fa-fw fa-facebook-f"></i>
                  </a>
                  <a class="btn btn-outline-light btn-social mx-1" target="_blank" title="Partager sur Twitter" rel="noreferrer" href="https://twitter.com/home?status={{ Request::url() }}">
                    <i class="fab fa-fw fa-twitter"></i>
                  </a>
                </div>

                <!-- Footer About Text -->
                <div class="col-lg-4">

                </div>

              </div>
            </div>
        </footer>

        <section class="p-4 text-center">

                <p><small>Ce site fait avec &#9829; vous est proposé gratuitement.<br> Si vous souhaitez le soutenir vous pouvez <a href="/faire-un-don">faire un don</a>, <a href="https://github.com/mchev/blinest" title="Github">participer au développement</a>, ou désactiver le bloqueur de publicité.</small></p>

        </section>


        <section class="copyright py-4 text-center text-white">
            <div class="container">

                <ul class="list-inline">
                    @foreach($categories as $cat)

                        <li class="list-inline-item"><a href="/parties/{{ $cat->slug }}" title="Blind-test {{ $cat->title }}">{{ $cat->title }}</a></li>

                    @endforeach

                </ul>

                <hr>

                <small>Blinest {{ date("Y") }} - <a href="/contact" title="Contact">Contact</a> - <a href="/lab" title="Le lab">Le lab</a> - <a href="/mentions-legales" title="Mentions légales">Mentions légales</a> - <a href="/politique-confidentialite" title="Politique de confidentialité">Politique de confidentialité</a> - <a href="https://github.com/mchev/blinest" title="Github">Open Source</a></small>

            </div>

        </section>

    </div>

    @yield('scripts')

    @include('cookieConsent::index')

</body>
</html>
