@extends('layouts.app')

@section('title')
  Blind-Test - {{ $game->title }}
@endsection

@section('description')
  Blind-Test - {{ $game->title }}. {{ $game->description }}
@endsection

@section('content')

  <multiplayer :game="{{ $game->toJson() }}" :user="{{ Auth::user()->toJson() }}"></multiplayer>

  @if(Auth::check())
    <section class="page-section bg-primary text-white text-center mb-0">
      <div class="container">

        <!-- About Section Heading -->
        <h2 class="page-section-heading text-uppercase">Ajoutes ton morceau préféré dans la liste</h2>

        <add-track :game="{{ $game }}"></add-track>
        
      </div>
    </section>
  @endif

@endsection