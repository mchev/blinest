@extends('layouts.app')

@section('title', 'Stream')
@section('description', 'Simple et efficace! Blind-tests multijoueurs, gratuit et sans inscription. Disney, Séries, Films, Années 80, etc.')

@section('content')

  <header class="masthead bg-primary text-white text-center">
      <div class="container d-flex align-items-center flex-column">

        <!-- Masthead Heading -->
        <h1 class="masthead-heading text-uppercase mb-0">Blind-Test</h1>

        <!-- Icon Divider -->
        <div class="divider-custom divider-light">
          <div class="divider-custom-line"></div>
          <div class="divider-custom-icon">
            <i class="fas fa-headphones"></i>
          </div>
          <div class="divider-custom-line"></div>
        </div>

        <!-- Masthead Subheading -->
        <p class="masthead-subheading font-weight-light mb-0">Testez votre culture musicale</p>

      </div>
  </header>

  <stream :game="{{ $game }}"></stream>

@endsection