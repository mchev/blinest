@extends('layouts.app')

@section('title', 'Le laboratoire - Blind-Test')
@section('description', 'Un espace ouvert à tous pour vos suggestions, idées, remontées de bugs.')

@section('content')

  <header class="masthead bg-primary text-white text-center">
      <div class="container d-flex align-labs-center flex-column">

        <!-- Masthead Heading -->
        <h1 class="masthead-heading text-uppercase mb-0">Le laboratoire blinest</h1>

        <a href="{{ route('lab.index') }}" class="btn btn-secondary mt-4">Retour au lab</a> 

      </div>
  </header>

  <section class="container my-4">

    <div class="card mt-3">
      <div class="card-header"> 
        <strong>#{{ $lab->id }}</strong> <span class="badge 
          @if($lab->type == 'Amélioration') badge-info @endif
          @if($lab->type == 'Idée') badge-success @endif
          @if($lab->type == 'Sécurité') badge-warning @endif
          @if($lab->type == 'BUG') badge-danger @endif
        ">
        {{ $lab->type }}</span> <span class="badge badge-secondary">{{ $lab->theme }}</span>
        <div class="float-right">
          {{ count($lab->voteUp) }} <a href="/lab/{{ $lab->id }}/vote?up=1"><i class="fas fa-thumbs-up"></i></a>
          {{ count($lab->voteDown) }} <a href="/lab/{{ $lab->id }}/vote?down=1" class="text-danger"><i class="fas fa-thumbs-down"></i></a>
        </div>
      </div>
      <div class="card-body"> 

        {{ $lab->body }}

      </div>
      <div class="card-footer"> 
        <small>Posté par <a href="{{ route('user.profil', $lab->user) }}">{{ $lab->user->name }}</a> le {{ $lab->created_at->format('d/m/Y à H:i') }}</small>

        <div class="float-right">

          @if($lab->planned_at) <span class="badge badge-info">Planifié</span> @endif
          @if($lab->opened_at) <span class="badge badge-warning">En cours de développement depuis le {{ $lab->opened_at->format('d/m/Y') }}</span> @endif
          @if($lab->closed_at) <span class="badge badge-danger">Clos le {{ $lab->closed_at->format('d/m/Y') }}</span> @endif
          @if($lab->rejected_at) <span class="badge badge-danger">Rejeté le {{ $lab->rejected_at->format('d/m/Y') }}</span> @endif

          @if(Auth::user()->is('admin'))

            <form method="post" style="display:inline-block;" action="/lab/{{ $lab->id }}" class="card">

              @method('PATCH')
              @csrf

              <select class="form-control p-0" name="action" onchange="this.form.submit()">
                <option selected="selected" disabled>Modifier le status...</option>
                <option value="plan">Planifier</option>
                <option value="open">En cours</option>
                <option value="close">Clos</option>
                <option value="reject">Rejeter</option>
                <option value="delete">Supprimer</option>
              </select>

            </form>

          @endif

        </div>

      </div>
    </div>

    @if(count($lab->childs) > 0)

      @foreach($lab->childs as $item)

        <div class="card mt-2 ml-5">

          <div class="card-body">
            {{ $item->body }}
          </div>

          <div class="card-footer">
            <small>Posté par <a href="{{ route('user.profil', $item->user) }}">{{ $item->user->name }}</a> le {{ $item->created_at->format('d/m/Y à H:i') }}</small>

            <div class="float-right">
              {{ count($item->voteUp) }} <a href="/lab/{{ $item->id }}/vote?up=1"><i class="fas fa-thumbs-up"></i></a>
              {{ count($item->voteDown) }} <a href="/lab/{{ $item->id }}/vote?down=1" class="text-danger"><i class="fas fa-thumbs-down"></i></a>
            </div>

          </div>

        </div>

      @endforeach

    @endif

  <form method="post" action="/lab" accept-charset="UTF-8" class="card mt-2 ml-5">

    @method('POST')
    @csrf

    <input type="hidden" name="lab_id" value="{{ $lab->id }}">

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

      <div class="form-group">
        <textarea class="form-control" placeholder="Commentaire..." name="body"></textarea>
      </div>

      <div class="form-group">
        <button class="btn btn-success" type="submit"><i class="fas fa-paper-plane"></i> Répondre</button>
      </div>

    </div>

  </form>

  </section>


@endsection