@extends('layouts.app')

@section('title', 'Profil - Blind-Test')
@section('description', 'Informations et statistiques d\'un compte utilisateur.')


@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">{{ $user->name }}</h1>

      @foreach ($user->roles as $role)

        <span class="badge badge-info">{{ $role->name }}@if($role->game()) de {{ $role->game()->title }}@endif</span>

      @endforeach

      <p class="mt-4">
        Inscrit.e depuis le {{ $user->created_at->format('d/m/Y') }}

        @if($user->latestScore)
          <br>
          Dernière partie jouée le : {{ $user->latestScore->created_at->format('d/m/Y H:i') }}
          <br>
          Nombre de parties jouées : {{ $user->scores->count() }}
        @endif

      </p>

</header>

<section class="page-section portfolio text-center" id="portfolio">
    <div class="container">

      <!-- Portfolio Section Heading -->
      <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Statistiques</h2>

      <!-- Icon Divider -->
      <div class="divider-custom">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <div class="divider-custom-line"></div>
      </div>


      @if($user->latestScore)

        <div class="row">

          <div class="col">

            <div class="table-responsive">
              <table class="table table-striped text-left">

                <thead>
                  <tr>
                    <th>Parties</th>
                    <th>Dernière partie le</th>
                    <th>Parties jouées</th>
                    <th>Meilleur score</th>
                    <th>Moyenne</th>
                    <th>Score total</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach($user->stats() as $stat)
                    <tr>
                      <td><a href="/parties/{{ $stat->game->slug }}">{{ $stat->game->title }}</a></td>
                      <td>{{ \Carbon\Carbon::parse($stat->latest)->format('d/m/Y H:i') }}</td>
                      <td>{{ $stat->total }}</td>
                      <td>{{ $stat->score }} pts</td>
                      <td>{{ $stat->average }} pts</td>
                      <td>{{ $stat->scores }} pts</td>
                    </tr>
                  @endforeach
                </tbody>

              </table>
            </div>

          </div>

        </div>

        <div class="row mt-4">

          <div class="col-md-12">

            <h2>Historique des parties</h2>

            {{ $scores->links() }}

            <div class="table-responsive">
              <table class="table table-striped">

                <thead>
                  <tr>
                    <th>Parties</th>
                    <th>Date</th>
                    <th>Scores</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach($scores as $score)
                    <tr>
                      <td><a href="/parties/{{ $score->game->slug }}">{{ $score->game->title }}</a></td>
                      <td>{{ $score->created_at->format('d/m/Y H:i') }}</td>
                      <td>{{ $score->score }}</td>
                    </tr>
                  @endforeach
                </tbody>

              </table>
            </div>


          </div>

        </div>

      @else

        <p>Aucune partie jouée</p>

      @endif

    </div>
</section>

@endsection