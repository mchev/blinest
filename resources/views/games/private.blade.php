@extends('layouts.app')

@section('title')
  Blind-Test - {{ $game->title }}
@endsection

@section('description')
  Blind-Test - {{ $game->title }}. {{ $game->description }}
@endsection

@section('content')

   @if(Auth::check())
    <multiplayer :game="{{ $game->toJson() }}" :user="{{ Auth::user()->toJson() }}"></multiplayer>
  @else
    <game :game="{{ $game->toJson() }}"></game>
  @endif

  @if( (Auth::check() && Auth::user()->is('moderator')) || (Auth::check() && Auth::user() == $game->user))
    <section class="page-section bg-primary text-white text-center mb-0">
      <div class="container">

        <!-- About Section Heading -->
        <h2 class="page-section-heading text-uppercase">Options</h2>

        <!-- Icon Divider -->
        <div class="divider-custom divider-light">
          <div class="divider-custom-line"></div>
          <div class="divider-custom-icon">
            <i class="fas fa-edit"></i>
          </div>
          <div class="divider-custom-line"></div>
        </div>

        
        <a  class="my-2 btn btn-info" href="{{ route('games.edit', $game) }}"><i class="fas fa-edit"></i> Editer la playlist</a>
        

      </div>
    </section>
  @endif

@endsection