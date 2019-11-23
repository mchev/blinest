@extends('layouts.app')

@section('title', 'Dernières sorties - Blind-Test')
@section('description', 'Les dernières sorties d’album en CD ou vinyle de vos chanteurs préférés.')

@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">Nouveautés</h1>

      <!-- Icon Divider -->
      <div class="divider-custom divider-light">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon">
          <i class="fas fa-compact-disc"></i>
        </div>
        <div class="divider-custom-line"></div>
      </div>

      <!-- Masthead Subheading -->
      <p class="masthead-subheading font-weight-light mb-0">Les dernières sorties d’album de vos chanteurs préférés.</p>

    </div>
</header>

<section class="page-section portfolio" id="portfolio">
    <div class="container">

      <div class="row">

        @foreach($releases as $release)

          <div class="col-md-3 mb-4 d-flex align-items-stretch">

            <div class="card">
              <img class="card-img-top" src="{{ $release->images[1]->url }}" alt="{{ $release->name }}">
              <div class="card-body">
                <h4 class="card-title"><a href="{{ $release->external_urls->spotify }}">{{ $release->artists[0]->name }} - {{ $release->name }}</a></h4>
                <p class="card-text">
                  Date de sortie : {{ date('d/m/Y', strtotime($release->release_date)) }}<br>
                  Nombre de pistes : {{ $release->total_tracks }}
                </p>
              </div>
              <div class="card-footer">
                <a href="{{ $release->external_urls->spotify }}" class="btn btn-success btn-block"><i class="fab fa-spotify"></i> Découvrir</a>
              </div>
            </div>

          </div>

        @endforeach

      </div>

    </div>
</section>


@endsection