@extends('layouts.app')

@section('title', 'Blind-Tests multijoueurs - gratuit et sans inscription')
@section('description', 'Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc.')

@section('content')

<header class="masthead pb-4 bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">Blind-Test</h1>

      <!-- Masthead Subheading -->
      <p class="masthead-subheading font-weight-light mb-0">Testez votre culture musicale</p>

      <div class="alert alert-warning">
        En raison d'une trop grande affluence sur le site, les parties privées ont momentanément été desactivées.<br>On bosse dessus et on revient avec des mises à jour au plus vite. Merci pour votre compréhension.
      </div>

    </div>
</header>

<section class="page-section portfolio" id="portfolio">
    <div class="container">

      <!-- Portfolio Section Heading -->
      <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Les quiz musicaux {{ date("Y") }}</h2>

      <!-- Icon Divider -->
      <div class="divider-custom">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon">
          <i class="fas fa-gamepad"></i>
        </div>
        <div class="divider-custom-line"></div>
      </div>

      <!-- Portfolio Grid Items -->
      <games :games="{{ $games->toJson() }}"></games>
      <!-- /.row -->

    </div>
</section>

<section class="page-section bg-primary text-white mb-0" id="about">
    <div class="container">

      <!-- About Section Heading -->
      <h2 class="page-section-heading text-center text-uppercase text-white">à propos</h2>

      <!-- Icon Divider -->
      <div class="divider-custom divider-light">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon">
          <i class="fas fa-question"></i>
        </div>
        <div class="divider-custom-line"></div>
      </div>

      <!-- About Section Content -->
      <div class="row text-justify">
        <div class="col-lg-4 ml-auto">
          <p class="lead">Blinest est un site de <strong>quiz musicaux</strong> qui se veut simple d'utilisation et entièrement <strong>gratuit</strong>.</p>
          <p class="lead">Vous n'avez pas besoin de vous inscrire pour lancer une partie.</p>
          <p class="lead">Les inscriptions permettent de choisir un pseudonyme, de créer des playlists privées et d'apparaître dans le classement.</p>
        </div>
        <div class="col-lg-4 mr-auto">
          <p class="lead"><strong>Comptage des points</strong></p>
          <ul class="lead">
            <li>Trouver l'artiste : 0.5 points</li>
            <li>Trouver le titre de l'extrait : 0.5 points</li>
            <li>Le titre du film ou de la série : 1 point</li>
            <li>Bonus rapidité : 0.5 points</li>
          </ul>
          <p class="lead">Pour éditer le contenu des parties publiques, <a href="/contact" class="text-white" title="Contact">contactez-nous</a> avec vos motivations et devenez modérateur.</p>
        </div>
      </div>

      <!-- About Section Button -->
      <div class="text-center mt-4">
        <button class="btn btn-xl btn-outline-light">
          <i class="fas fa-music"></i>
          {{ $counter }} extraits!
        </button>
      </div>

    </div>
</section>

@endsection