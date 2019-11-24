<nav id="mainNav" class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="/img/logo_svg.svg" alt="{{ config('app.name', 'Laravel') }}">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('releases') }}">Actualités</a>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else

                    @if(Auth::user()->is('admin'))
                        <li class="nav-item"><a href="{{ route('admin.dashboard.index') }}" class="nav-link">Dashboard</a></li>
                        <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link">Utilisateurs</a></li>
                        <li class="nav-item"><a href="{{ route('admin.tracks.index') }}" class="nav-link">Tracks</a></li>
                        <li class="nav-item"><a href="{{ route('admin.games.index') }}" class="nav-link">Parties</a></li>
                    @endif


                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            @if(Auth::user()->is('admin'))<a class="dropdown-item" href="{{ route('admin.dashboard.index') }}">Administration</a>@endif
                            <a class="dropdown-item" href="{{ route('games.create') }}">Créer un blind test</a>
                            <a class="dropdown-item" href="{{ route('games.me') }}">Mes parties</a>
                            <a class="dropdown-item" href="{{ route('profile') }}">Mon compte</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>