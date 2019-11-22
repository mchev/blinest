<!-- create.blade.php -->

@extends('layouts.app')

@section('title')
  Création d'un blind-test
@endsection

@section('description')
  Créer un nouveau blind-test public ou privé.
@endsection

@section('content')

  <header class="masthead bg-primary text-white text-center">
      <div class="container d-flex align-items-center flex-column">

        <!-- Masthead Heading -->
        <h1 class="masthead-heading text-uppercase mb-0">Créer un Blind-Test</h1>

        <!-- Icon Divider -->
        <div class="divider-custom divider-light">
          <div class="divider-custom-line"></div>
          <div class="divider-custom-icon">
            <i class="fas fa-star"></i>
          </div>
          <div class="divider-custom-line"></div>
        </div>

      </div>
  </header>

  <section class="page-section">
    <div class="container">

  <div class="row">

    <div class="col-md-8 mx-auto">

      <div class="card">

        <div class="card-header">
          Création d'un Blind Test
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

          <form method="post" enctype="multipart/form-data" action="{{ route('games.store') }}">

              @csrf

              <div class="form-group">
                  <label for="title">Titre</label>
                  <input type="text" class="form-control" id="title" name="title" required/>
              </div>

              <div class="form-group">
                  <label for="description">Description</label>
                  <input type="text" class="form-control" id="description" name="description" required/>
              </div>

              <div class="form-group">
                  <label for="thumbnail">Image</label>
                  <input type="file" class="form-control" id="thumbnail" name="thumbnail" required/>
              </div>

              <hr class="mt-4">

              @if(Auth::user()->is('admin'))

                <div class="form-group">
                  <label>Visibilité du blind test</label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="public" id="public1" value="0" checked>
                    <label class="form-check-label" for="public1">
                      Privé
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="public" id="public2" value="1">
                    <label class="form-check-label" for="public2">
                      Publique
                    </label>
                  </div>
                </div>

                <hr>

              @endif


              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" name="random" id="random" checked>
                  <label class="form-check-label" for="random">
                    Lecture aléatoire
                  </label>
                </div>
              </div>

              <hr>

              <div class="form-group">
                  <label for="tracks_number">Nombre d'extraits par partie</label>
                  <input type="number" class="form-control" value="12" step="1" id="tracks_number" name="tracks_number" required/>
              </div>


              <button type="submit" class="btn btn-primary">Enregistrer</button>

          </form>

        </div>
      </div>

    </div>

  </div>

</div>
</section>

@endsection