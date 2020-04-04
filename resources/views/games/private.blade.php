@extends('layouts.app')

@section('title')
  Blind-Test - {{ $game->title }}
@endsection

@section('description')
  Blind-Test - {{ $game->title }}. {{ $game->description }}
@endsection

@section('content')

  @if($game->tracks->count() > 50)

    <game :game="{{ $game->toJson() }}" :user="{{ Auth::user() ? Auth::user() : 'null' }}"></game>

  @else 

      <header class="masthead bg-primary text-white text-center pb-2">

          <div class="container d-flex align-items-center flex-column">

              <!-- Masthead Heading -->
              <h1 class="masthead-heading text-uppercase mb-0">Blind-Test {{ $game->title }}</h1>

              <p class="masthead-subheading font-weight-light mb-0">{{ $game->description }}</p>

              <div class="mt-5 alert alert-warning">
                <strong>Cette partie ne contient que {{$game->tracks->count()}} extraits sur 50 minimum, ce qui est insuffisant pour la lancer.</strong><br>
                Si tu es le créateur de cette partie, rends toi sur <a href="{{ route('games.me') }}">Mes parties</a> pour l'éditer.<br>
                Sinon tu peux aider le créateur à remplir la playlist en ajoutant des extraits dans le champs ci-dessous.
              </div>

          </div>

      </header>

  @endif
  
  @if( Auth::check() )
    <section class="page-section bg-primary text-white text-center mb-0">
      <div class="container">

        <!-- About Section Heading -->
        <h3 class="page-section-heading text-uppercase">Ajoutes tes morceaux préférés sur le thème {{ $game->title }}</h3>

        <add-track :game="{{ $game }}"></add-track>
        

      </div>
    </section>
  @endif

@endsection