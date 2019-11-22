@extends('layouts.admin')

@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">Tracks</h1>
      <p class="masthead-subheading font-weight-light mb-0">{{ $tracks->total() }} Tracks</p>

    </div>
</header>

<section class="page-section portfolio" id="portfolio">

  <div class="container-fluid">

    <div class="row m-2">

      <div class="col">
        <a href="/admin/tracks/duplicates" class="btn btn-secondary">Afficher les doublons</a>
      </div>

      <div class="col">
        {{ $tracks->links() }}
      </div>

    </div>

      <div class="row row-table">

        <div class="col col-th">#</div>
        <div class="col-2 col-th">Artiste</div>
        <div class="col-2 col-th">Titre</div>
        <div class="col-2 col-th">Réponse</div>
        <div class="col-2 col-th">Extrait</div>
        <div class="col-2 col-th">Partie</div>
        <div class="col col-th">Signalement</div>
        <div class="col col-th"></div>

      </div>


      @foreach ($tracks as $track)

          <form method="POST" class="row row-table" action="{{ route('admin.tracks.update', $track->id) }}">

            @csrf
            @method('PUT')

            <div class="col col-th">{{ $track->id }}</div>
            <div class="col-2"><input type="text" name="artist_name" class="form-control" value="{{ $track->artist_name }}"></div>
            <div class="col-2"><input type="text" name="track_name" class="form-control" value="{{ $track->track_name }}"></div>
            <div class="col-2"><input type="text" name="custom_answer" class="form-control" value="{{ $track->custom_answer }}"></div>
            <div class="col-2"><audio src="{{ $track->preview_url }}" preload="none" controls /></div>
            <div class="col-2">{{ $track->game->title }}</div>
            <div class="col"><input type="number" name="down_rate" class="form-control" value="{{ $track->down_rate }}"></div>
            <div class="col">
              <button class="btn btn-sm btn-success" title="Enregistrer" type="submit"><i class="fas fa-save"></i></button>
              <button class="btn btn-sm btn-danger" name="delete" value="delete" title="Supprimer" onsubmit="return confirm('Êtes-vous sûr?');" type="submit"><i class="fas fa-trash"></i></button>
            </div>

          </form>

      @endforeach


  </div>

</section>


@endsection