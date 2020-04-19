@extends('layouts.app')

@section('title')
  Blind-Test - {{ $game->title }}
@endsection

@section('description')
  Blind-Test - {{ $game->title }}. {{ $game->description }}
@endsection

@section('content')

  <game :game="{{ $game->toJson() }}" :user="{{ Auth::user() ? Auth::user() : 'null' }}"></game>
  
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