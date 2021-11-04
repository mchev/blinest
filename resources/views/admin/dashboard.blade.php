@extends('layouts.admin')

@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">Dashboard</h1>

      {{--

      <form method="post" enctype="multipart/form-data" action="{{ route('admin.dashboard.index') }}">

        @method('POST')
        @csrf

        <div class="row mt-4">

          <div class="col">
            <input type="date" name="startDate" class="form-control" value="{{ $analytics['startDate']->format('Y-m-d') }}">
          </div>
          <div class="col">
            <input type="date" name="endDate" class="form-control" value="{{ $analytics['endDate']->format('Y-m-d') }}">
          </div>
          <div class="col">
            <button class="btn btn-info" type="submit">Mettre à jour</button>
          </div>

        </div>

      </form>

      --}}

    </div>
</header>

<section class="page-section pb-0">

  <div class="container-fluid">


  <div class="row my-4">

{{--
    <!-- Visitors -->
    <div class="col">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Visiteurs</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $analytics['totalVisitors'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- PAge Views -->
    <div class="col">
      <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Pages vues</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $analytics['totalPageViews'] }}</div>
            </div>
            <div class="col-auto">
              <i class="far fa-eye fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
--}}

    <!-- Users -->
    <div class="col">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Inscrits</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users_count }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-signature fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Games -->
    <div class="col">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Parties</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $games_count }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-gamepad fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--Tracks -->
    <div class="col">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tracks</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tracks_count }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-music fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!--Scores -->
    <div class="col">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Parties jouées</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $scores_count }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-star fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>

</section>

<section class="page-section pt-0">

  <div class="container-fluid">

    <div class="row mb-3">

      <div class="col">

        <a href="{{ route('admin.games.clean') }}" class="btn btn-warning">Nettoyer les parties privées</a>
        <small>Supprime définitivement toutes les parties mises à jours il y a plus d'un an et ne contenant aucun extraits.</small>

      </div>

    </div>

  </div>

</section>

{{--
<section class="page-section pt-0">

  <div class="container-fluid">

    <div class="row mb-3">

      <div class="col">

        <total-visitors-and-page-views :datas="{{ $analytics['TotalVisitorsAndPageViews']->toJson() }}"></total-visitors-and-page-views>

      </div>

    </div>

    <div class="row mb-3">

      <div class="col-md-3">

        <h3>Les plus visitées</h3>

        <ul class="list-group">

          @foreach($analytics['MostVisitedPages'] as $page)

            <li class="list-group-item d-flex justify-content-between align-items-center">
              <a href="{{ $page['url'] }}">{{ $page['pageTitle'] }}</a>
              <span class="badge badge-primary badge-pill">{{ $page['pageViews'] }}</span>
            </li>

          @endforeach

        </ul>
          
      </div>

      <div class="col-md-3">

        <h3>Navigateurs</h3>

        <ul class="list-group">

          @foreach($analytics['TopBrowsers'] as $browser)

            <li class="list-group-item d-flex justify-content-between align-items-center">
              {{ $browser['browser'] }}
              <span class="badge badge-primary badge-pill">{{ $browser['sessions'] }}</span>
            </li>

          @endforeach

        </ul>
          
      </div>


      <div class="col-md-3">

        <h3>Origines</h3>

        <ul class="list-group">

          @foreach($analytics['TopReferrers'] as $referer)

            <li class="list-group-item d-flex justify-content-between align-items-center">
              {{ $referer['url'] }}
              <span class="badge badge-primary badge-pill">{{ $referer['pageViews'] }}</span>
            </li>

          @endforeach

        </ul>
          
      </div>


      <div class="col-md-3">

        <h3>Fidélisation</h3>

        <user-types :datas="{{ $analytics['UserTypes']->toJson() }}"></user-types>
          
      </div>

    </div>

  </div>

</section>

--}}

@endsection