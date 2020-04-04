@extends('layouts.app')

@section('title')
  Blind-Test - Parties privées
@endsection

@section('description')
  Blind-Test privés, jouez sans limite avec vos propres sélections.
@endsection

@section('content')

  <header class="masthead bg-primary text-white text-center">
      <div class="container d-flex align-items-center flex-column">

        <!-- Masthead Heading -->
        <h1 class="masthead-heading text-uppercase mb-0">Mes parties privées</h1>

        <!-- Icon Divider -->
        <div class="divider-custom divider-light">
          <div class="divider-custom-line"></div>
          <div class="divider-custom-icon">
            <i class="fas fa-headphones"></i>
          </div>
          <div class="divider-custom-line"></div>
        </div>

        <a href="{{ route('games.create') }}" class="btn btn-success btn-lg">Créer une partie privée</a>

      </div>
  </header>

  <section class="page-section portfolio" id="portfolio">
      <div class="container">

        <!-- Portfolio Grid Items -->
        <games :games="{{ $games->toJson() }}"></games>
        <!-- /.row -->

      </div>
  </section>

@endsection