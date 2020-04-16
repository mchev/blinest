@extends('layouts.app')

@section('title')
  Blind-Test - {{ $game->title }}
@endsection

@section('description')
  Blind-Test - {{ $game->title }}. {{ $game->description }}
@endsection

@section('content')

  <section class="custom-game">

    <custom-game :game="{{ $game }}"></custom-game>

  </section>

@endsection