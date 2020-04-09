@extends('layouts.app')

@section('title', 'Le laboratoire - Blind-Test')
@section('description', 'Un espace ouvert à tous pour vos suggestions, idées, remontées de bugs.')

@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">Le laboratoire blinest</h1>

      <!-- Masthead Subheading -->
      <p class="masthead-subheading font-weight-light mb-0">Un espace ouvert à tous pour vos suggestions, idées, remontées de bugs.</p>
      <small>Pour signaler une erreur sur un extrait, cliquez sur le pouce rouge dans les résultats de la partie.</small> 

    </div>
</header>

<section class="container my-4">

  <form method="post" action="/lab" accept-charset="UTF-8" class="card">

    @method('POST')
    @csrf

    <div class="card-header">

      <h3>Créer un nouveau ticket</h3>
      <small>Avant de créer un nouveau ticket, vérifier que personne n'a déjà créé un ticket sur le sujet</small>

    </div>

    <div class="card-body">

      @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif

      <div class="form-group mt-4">
        <label>Thème</label>
        <select class="form-control" name="theme">
          <option value="Toutes les parties">Toutes les parties</option>
          <option value="Parties publiques">Parties publiques</option>
          <option value="Partie privées">Partie privées</option>
          <option value="Actualités">Actualités</option>
          <option value="Compte et Profils">Comptes et Profils</option>
          <option value="Laboratoire">Laboratoire</option>
          <option value="Divers">Divers</option>
        </select>
      </div>

      <div class="form-group mt-4">
        <label>Type de ticket</label>
        <select class="form-control" name="type">
          <option value="Amélioration">Amélioration</option>
          <option value="Idée">Idée</option>
          <option value="Sécurité">Sécurité</option>
          <option value="BUG">BUG</option>
        </select>
      </div>

      <div class="form-group">
        <label>Votre message</label>
        <textarea class="form-control" placeholder="Expliques clairement ton besoin (page, lien, fonctionnalité) avec des phrases complètes. Ceci n'est pas un chat, attention à l'orthographe." name="body"></textarea>
      </div>

      <div class="form-group">
        <a class="btn btn-secondary" href="{{ route('lab.index') }}"><i class="fas fa-chevron-left"></i> Retour au lab</a>
        <button class="btn btn-success" type="submit"><i class="fas fa-paper-plane"></i> Poster</button>
      </div>

    </div>

  </form>

</section>


@endsection