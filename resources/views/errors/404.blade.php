@extends('layouts.app')

@section('title', 'Erreur 404 - Blind-Test')
@section('description', 'La page que vous demandez est introuvable.')

@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">Erreur 404</h1>

      <!-- Icon Divider -->
      <div class="divider-custom divider-light">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon">
          <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="divider-custom-line"></div>
      </div>

      <!-- Masthead Subheading -->
      <p class="masthead-subheading font-weight-light mb-0">La page que vous demandez est introuvable</p>
      <a href="/" class="btn btn-lg btn-info m-4">Retourner à l'accueil</a>
      
    </div>
</header>

@endsection