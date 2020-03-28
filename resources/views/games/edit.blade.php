<!-- edit.blade.php -->

@extends('layouts.app')

@section('title')
  Modification d'un blind-test
@endsection

@section('description')
  Modifier un lind-test public ou privé.
@endsection

@section('content')

<div class="masthead container-fluid">

  <div class="row">

    <div class="col-md-12 mb-4">

      <div class="row">

        <div class="col-md-6">
          <h1>{{ $game->title }}</h1> 
        </div>
        <div class="col-md-6 text-right">
          <a class="btn btn-lg btn-success" href="/parties/{{ $game->slug }}">Rejoindre la partie</a>
        </div>
      
      </div>

      <game-edit :game="{{ $game->toJson() }}"></game-edit>

    </div>

    <div class="col-md-12">

      <div class="card">

        <div class="card-header">
          {{ $game->title }} - Paramètres
        </div>

        <div class="card-body">

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
              </ul>
            </div><br />
          @endif

          <form method="post" enctype="multipart/form-data" action="{{ route('games.update', $game->id) }}">

              @method('PATCH')
              @csrf

              <div class="form-group">
                  <label for="title">Titre</label>
                  <input type="text" class="form-control" id="title" name="title" value="{{ $game->title }}" required/>
              </div>

              <div class="form-group">
                  <label for="description">Description</label>
                  <input type="text" class="form-control" id="description" name="description" value="{{ $game->description }}" required/>
              </div>

              <hr>

              <div class="row">

                <div class="col-md-12">

                  <div class="form-group">
                      <label class="btn-file btn btn-primary" for="thumbnail">
                        Modifier l'image
                        <input type="file" class="form-control" id="thumbnail" name="thumbnail"/>
                      </label>
                  </div>

                  @if($game->thumbnail)
                    <img id="thumbnail-img" class="img-fluid img-thumbnail" src="/storage/games/{{ $game->thumbnail }}">
                  @endif

                </div>

              </div>

              <hr>

              <button type="submit" class="btn btn-success">Enregistrer les modifications</button>

          </form>

        </div>
      </div>


      <div class="card mt-4">

          <div class="card-header">
            Supprimer ce Blind test
          </div>

          <div class="card-body">

            <div class="alert alert-danger" role="alert">
              Si vous décidez de supprimer ce blind test, toutes les musiques et statistiques associées seront définitivement effacées.
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ route('games.destroy', $game->id) }}">

                @method('DELETE')
                @csrf

                <button type="submit" class="btn btn-danger">Supprimer (Cette action est irréversible.)</button>

            </form>

          </div>

      </div>

    </div>

  </div>

</div>

@endsection


@section('scripts')

  <script>

    function readURL(input) {

      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#thumbnail-img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    window.onload = function () {

      $("#thumbnail").change(function() {
        readURL(this);
      });

    }

  </script>

@endsection