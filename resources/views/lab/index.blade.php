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

      <img src="/img/lab.png" width="200px">

      <a href="{{ route('lab.create') }}" class="btn btn-info mt-4">Créer un nouveau ticket</a> 

    </div>
</header>

<section class="container my-4">


  <ul class="nav nav-tabs" id="labTabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#pending">Ouverts (@if($labs) {{ $labs->pending()->count() }} @else 0 @endif)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#closed">Clos (@if($labs) {{ $labs->closed()->count() }} @else 0 @endif)</a>
    </li>
  </ul>


  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
      @if($labs)

        @foreach($labs->pending() as $item)

          <div class="card mt-3">
            <div class="card-header"> 
              <strong>#{{ $item->id }}</strong>
              <a href="{{ route('lab.show', $item) }}"><i class="far fa-eye"></i></a>
              <span class="badge 
                @if($item->type == 'Amélioration') badge-info @endif
                @if($item->type == 'Idée') badge-success @endif
                @if($item->type == 'Sécurité') badge-warning @endif
                @if($item->type == 'BUG') badge-danger @endif
              ">
              {{ $item->type }}</span>
              <span class="badge badge-secondary">{{ $item->theme }}</span>
              <div class="float-right">
                {{ count($item->voteUp) }} <a href="/lab/{{ $item->id }}/vote?up=1"><i class="fas fa-thumbs-up"></i></a>
                {{ count($item->voteDown) }} <a href="/lab/{{ $item->id }}/vote?down=1" class="text-danger"><i class="fas fa-thumbs-down"></i></a>
              </div>
            </div>
            <div class="card-body"> 

              {{ $item->body }}

            </div>
            <div class="card-footer"> 
              <small>Posté par <a href="/user/profil/{{ $item->user->id }}">{{ $item->user->name }}</a> @if($item->user->is('admin')) <span class="badge badge-info">admin</span> @endif le {{ $item->created_at->format('d/m/Y à H:i') }}</small>

              <div class="float-right">

                <a class="badge badge-secondary" href="{{ route('lab.show', $item) }}">{{ count($item->childs) }} @if(count($item->childs) > 1) commentaires @else commentaire @endif</a> 

                @if($item->planned_at) <span class="badge badge-info">Planifié</span> @endif
                @if($item->opened_at) <span class="badge badge-warning">En cours de développement depuis le {{ $item->opened_at->format('d/m/Y') }}</span> @endif
                @if($item->closed_at) <span class="badge badge-danger">Clos le {{ $item->closed_at->format('d/m/Y') }}</span> @endif
                @if($item->rejected_at) <span class="badge badge-danger">Rejeté le {{ $item->rejected_at->format('d/m/Y') }}</span> @endif

                @if(Auth::user()->is('admin'))

                  <form method="post" style="display:inline-block;" action="/lab/{{ $item->id }}" class="card">

                    @method('PATCH')
                    @csrf

                    <select class="badge p-0" name="action" onchange="this.form.submit()">
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

        @endforeach

      @endif
    </div>

    <div class="tab-pane" id="closed" role="tabpanel" aria-labelledby="closed-tab">
      @if($labs)

        @foreach($labs->closed() as $item)

          <div class="card mt-3">
            <div class="card-header"> 
              <strong>#{{ $item->id }}</strong> <span class="badge 
                @if($item->type == 'Amélioration') badge-info @endif
                @if($item->type == 'Idée') badge-success @endif
                @if($item->type == 'Sécurité') badge-warning @endif
                @if($item->type == 'BUG') badge-danger @endif
              ">
              {{ $item->type }}</span> <span class="badge badge-secondary">{{ $item->theme }}</span>
              <div class="float-right">
                {{ count($item->voteUp) }} <a href="/lab/{{ $item->id }}/vote?up=1"><i class="fas fa-thumbs-up"></i></a>
                {{ count($item->voteDown) }} <a href="/lab/{{ $item->id }}/vote?down=1" class="text-danger"><i class="fas fa-thumbs-down"></i></a>
              </div>
            </div>
            <div class="card-body">

              {{ $item->body }}

            </div>
            <div class="card-footer"> 
              <small>Posté par <a href="/user/profil/{{ $item->user->id }}">{{ $item->user->name }}</a> @if($item->user->is('admin')) <span class="badge badge-info">admin</span> @endif le {{ $item->created_at->format('d/m/Y à H:i') }}</small>

              <div class="float-right">

                <a class="badge badge-secondary" href="{{ route('lab.show', $item) }}">{{ count($item->childs) }} @if(count($item->childs) > 1) commentaires @else commentaire @endif</a> 


                @if($item->closed_at) <span class="badge badge-danger">Clos le {{ $item->closed_at->format('d/m/Y') }}</span> @endif
                @if($item->rejected_at) <span class="badge badge-danger">Rejeté le {{ $item->rejected_at->format('d/m/Y') }}</span> @endif

                @if(Auth::user()->is('admin'))

                  <form method="post" style="display:inline-block;" action="/lab/{{ $item->id }}" class="card">

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

        @endforeach

      @endif
    </div>

  </div>

</section>


@endsection