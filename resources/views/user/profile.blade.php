@extends('layouts.app')

@section('title', 'Profil - Blind-Test')
@section('description', 'Informations et statistiques d\'un compte utilisateur.')


@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">{{ Auth::user()->name }}</h1>

      @foreach (Auth::user()->roles as $role)

        {{ $role->name }}

      @endforeach

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

      <h3 class="mt-4">Meilleurs Scores par parties</h3>

      <div class="table-responsive">
        <table class="table table-striped">

          <thead>
            <tr>
              <th>Parties</th>
              <th>Mon meilleur score</th>
              <th>Meilleur score</th>
            </tr>
          </thead>

          <tbody>
            @foreach($stats as $stat)
              <tr>
                <td><a href="/parties/{{ $stat->game->slug }}">{{ $stat->game->title }}</a></td>
                <td>{{ $stat->score }}</td>
                <td>
                  @foreach($bestScore as $best)
                    @if($best->game_id == $stat->game_id)
                      {{ $best->score }}
                    @endif
                  @endforeach
                </td>
              </tr>
            @endforeach
          </tbody>

        </table>
      </div>

      <h3 class="mt-4">Parties jouées ({{ $gamesCounter }})</h3>
      <stats-game-type :stats="{{ $stats->toJson() }}" :height="150"></stats-game-type>

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