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

      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="tracks-tab" data-toggle="tab" href="#tracks" role="tab" aria-controls="tracks" aria-selected="true">Gestion des extraits</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Paramètres</a>
        </li>
      </ul>

      <div class="tab-content">

        <div class="tab-pane active" id="tracks" role="tabpanel" aria-labelledby="tracks-tab">
          <game-edit class="my-3" :game="{{ $game->toJson() }}"></game-edit>
        </div>

        <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab">

          <div class="card my-3">

            <form method="post" enctype="multipart/form-data" action="{{ route('games.update', $game->id) }}">

              @method('PATCH')
              @csrf

              <div class="card-header bg-secondary text-white">
                Paramètres
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

                <div class="row">

                  <div class="col">

                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $game->title }}" required/>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" name="description" value="{{ $game->description }}" placeholder="255 caractères max" required/>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe de la partie</label>
                        <input type="text" class="form-control" id="password" name="password" value="{{ $game->password }}"/>
                    </div>

                    @if( Auth::user()->is('admin') )

                      <div class="form-group">
                          <label for="description">Couleur</label>
                          <input type="text" class="form-control" id="description" name="color" value="{{ $game->color }}" placeholder="Hexadecimal sans le #"/>
                      </div>

                      <div class="form-group">
                          <label for="description">Discord Webhook Url</label>
                          <input type="text" class="form-control" id="description" name="discord_webhook_url" value="{{ $game->discord_webhook_url }}"/>
                      </div>

                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="{{ $game->public }}" name="public" id="publicCheckDefault" @if($game->public) checked="checked" @endif>
                          <label class="form-check-label" for="publicCheckDefault">
                            Partie publique
                          </label>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="{{ $game->pro }}" name="pro" id="proCheckDefault" @if($game->pro) checked="checked" @endif>
                          <label class="form-check-label" for="proCheckDefault">
                            Partie pro
                          </label>
                        </div>
                      </div>

                    @endif

                  </div>

                  <div class="col">

                    <div class="custom-file my-3">
                      <input type="file" name="thumbnail" class="custom-file-input" id="inputGroupFileGameThumbnail" aria-describedby="inputGroupFileAddon01">
                      <label class="custom-file-label" for="inputGroupFileGameThumbnail">Modifier l'image</label>

                      @if($game->thumbnail)
                        <img id="thumbnail-img" class="img-fluid img-thumbnail mt-2" src="/storage/games/{{ $game->thumbnail }}">
                      @endif
                    </div>

                  </div>

                </div>

              </div>

              <div class="card-footer">

                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>

              </div>

            </form>

          </div>



          <div class="card my-3">

            <form method="post" enctype="multipart/form-data" action="{{ route('games.destroy', $game->id) }}" onsubmit="return confirm('Voulez-vous vraiment supprimer définitvement cette partie?');">

                @method('DELETE')
                @csrf

                <div class="card-header bg-secondary text-white">
                  Supprimer ce Blind test
                </div>

                <div class="card-body">

                  <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> Si vous décidez de supprimer ce blind test, <b>tous les scores, musiques et statistiques associés seront DEFINITIVEMENT effacés.</b>
                  </div>

                </div>

                <div class="card-footer">

                  <button type="submit" class="btn btn-danger text-uppercase">Supprimer la partie</button>

                </div>

            </form>

          </div>

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