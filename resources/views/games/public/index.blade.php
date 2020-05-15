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
          <game-edit :game="{{ $game }}"></game-edit>
        </div>
      </section>

    @endif

  @endif

@endsection