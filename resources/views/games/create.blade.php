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
                <div class="alert alert-info">Une fois votre partie enregistrée, vous devez ajouter un minimum de 50 extraits pour pouvoir jouer. Toutes les parties comprennent 15 extraits. Vous pouvez partager le lien avec d'autres joueurs.</div>
                <small>* Tous les champs sont obligatoires</small>
              </div>

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

              <div class="form-group text-right pt-3">
                <button type="submit" class="btn btn-primary">Suivant</button>
              </div>

          </form>

        </div>
      </div>

    </div>

  </div>

</div>
</section>

@endsection