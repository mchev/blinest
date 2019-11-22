@extends('layouts.admin')

@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">Parties</h1>

    </div>
</header>

<section class="page-section portfolio" id="portfolio">

  <div class="container">

    <div class="row m-2">

      <div class="col">
        <strong>{{ $games->total() }} Parties</strong>
      </div>

      <div class="col">
        {{ $games->links() }}
      </div>

    </div>

    <table class="table table-striped">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Titre</th>
          <th scope="col">Publique</th>
          <th scope="col">Tracks</th>
          <th scope="col">Hit</th>
        </tr>
      </thead>

      <tbody>

        @foreach ($games as $game)
          <tr>
            <th scope="row">{{ $game->id }}</th>
            <td><a href="{{ route('admin.games.edit', $game->id) }}">{{ $game->title }}</a></td>
            <td>@if($game->public)<div class="badge badge-info">Publique</div>@else<div class="badge badge-danger">Privée ({{ $game->user->name }})</div>@endif</td>
            <td>{{ $game->tracks->count() }}</td>
            <td>{{ $game->hit }}</td>
          </tr>        
        @endforeach

      </tbody>

    </table>

  </div>


</section>


@endsection