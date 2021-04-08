@extends('layouts.app')

@section('title')
  Blind-Test - {{ $game->title }}
@endsection

@section('description')
  Blind-Test - {{ $game->title }}. {{ $game->description }}
@endsection

@section('content')

  <section class="public-game">

    <public-game :game="{{ $game }}"></public-game>

  </section>

  @if( Auth::check() )

    @if(Auth::user()->isModerator($game))

      <section class="page-section bg-primary text-white text-center mb-0">
        <div class="container">
          <h3 class="page-section-heading text-uppercase">Administration de la partie</h3>
          <a class="btn btn-info mb-3" target="_blank" href="/moderator"><i class="fas fa-external-link-alt"></i> Rejoindre le salon des modérateurs</a>
          <game-edit :game="{{ $game }}"></game-edit>
        </div>
      </section>

    @endif

    <section class="page-section bg-primary text-white text-center mb-0">
      <div class="container">
        <h3>Envoyer un message aux modérateurs</h3>
        <create-ticket :game="{{ $game }}"></create-ticket>
      </div>
    </section>

  @endif

@endsection

@section('scripts')

  <!-- Game blinest -->
  <div id="pubGame" style="display:none;">
      <script async defer src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
      <ins class="adsbygoogle"
           style="display:inline-block;width:728px;height:90px"
           data-ad-client="ca-pub-6495635642797272"
           data-ad-slot="8419827522"></ins>
      <script>
           (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
  </div>

@endsection