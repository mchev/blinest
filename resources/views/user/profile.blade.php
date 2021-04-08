@extends('layouts.app')

@section('title', 'Profil - Blind-Test')
@section('description', 'Informations et statistiques d\'un compte utilisateur.')


@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">{{ Auth::user()->name }}</h1>

      <!-- Icon Divider -->
      <div class="divider-custom divider-light">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon">
          <i class="fas fa-id-card"></i>
        </div>
        <div class="divider-custom-line"></div>
      </div>

      <!-- Masthead Subheading -->
      <p class="masthead-subheading font-weight-light mb-0">{{ Auth::user()->email }}</p>

    </div>
</header>

<section class="page-section portfolio text-center" id="portfolio">
    <div class="container">

      <!-- Portfolio Section Heading -->
      <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Mes stats</h2>

      <!-- Icon Divider -->
      <div class="divider-custom">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <div class="divider-custom-line"></div>
      </div>

      <a  class="btn btn-primary" href="{{ route('user.profil', Auth::user()) }}">Voir mes stats</a>

    </div>
</section>

<section class="page-section bg-primary text-white text-center mb-0" id="about">
    <div class="container">

      <!-- About Section Heading -->
      <h2 class="page-section-heading text-center text-uppercase text-white">Mes infos</h2>

      <!-- Icon Divider -->
      <div class="divider-custom divider-light">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon">
          <i class="fas fa-info"></i>
        </div>
        <div class="divider-custom-line"></div>
      </div>

      <div class="card text-dark mb-4">

        <div class="card-header">
          <h5>Photo de profil</h5>
        </div>

        <div class="card-body">

          <form method="POST" enctype="multipart/form-data" action="{{ route('profile.picture.upload') }}">

              @csrf

              <div class="form-group row">
                  <label for="image" class="col-md-4 col-form-label text-md-right">
                    {{ __('Photo de profil') }}

                    @if(Auth::user()->hasProfilePicture())
                      <img src="/images/players/{{ Auth::user()->id }}.webp" class="rounded-circle my-4 d-block ml-auto" height="60" width="60">
                      <a href="{{ route('profile.picture.delete') }}" class="btn btn-sm btn-danger">Supprimer</a>
                    @endif
                    

                  </label>

                  <div class="col-md-6 text-left">

                      <small>Sélectionner un fichier pour ajouter ou remplacer la photo de profil</small>
                      <input id="image" type="file" accept="image/*" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" name="image">

                      @if ($errors->has('image'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('image') }}</strong>
                          </span>
                      @endif

                  </div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-success">{{ __('Mettre à jour') }}</button>
              </div>

          </form>

        </div>

      </div>

      <div class="card text-dark">

        <div class="card-header">
          <h5>Modifier mes informations</h5>
        </div>

        <div class="card-body">

          <form method="POST" action="{{ route('profile.update') }}">

              @csrf

              <div class="form-group row">
                  <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                  <div class="col-md-6">
                      <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ Auth::user()->name }}" required autofocus>

                      @if ($errors->has('name'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('name') }}</strong>
                          </span>
                      @endif
                  </div>
              </div>

              <div class="form-group row">
                  <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                  <div class="col-md-6">
                      <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ Auth::user()->email }}" required>

                      @if ($errors->has('email'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('email') }}</strong>
                          </span>
                      @endif
                  </div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
              </div>

          </form>

        </div>

      </div>

      <hr class="my-5">

      <p><a class="btn btn-info" href="/password/reset">Modifier mon mot de passe</a></p>

      <p class="mt-4"><a href="{{ route('profile.delete') }}" class="btn btn-danger" onclick="return confirm('Cette action supprimera définitivement votre compte, vos parties et statistiques. Êtes-vous certain.e?')">Supprimer mon compte définitivement</a></p>

    </div>
</section>


@endsection