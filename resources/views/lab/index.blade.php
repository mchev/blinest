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

      <a href="{{ route('lab.create') }}" class="btn btn-info mt-4">Créer un nouveau ticket</a> 

    </div>
</header>

<section class="container my-4">


  <ul class="nav nav-tabs" id="labTabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#pending">Ouverts (@if($labs) {{ $labs->pending()->count() }} @else 0 @endif)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#planned">Planifiés (@if($labs) {{ $labs->planned()->count() }} @else 0 @endif)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#opened">En cours (@if($labs) {{ $labs->opened()->count() }} @else 0 @endif)</a>
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
              <strong>#{{ $item->id }}</strong> <span class="badge 
                @if($item->type == 'Amélioration') badge-info @endif
                @if($item->type == 'Idée') badge-success @endif
                @if($item->type == 'Sécurité') badge-warning @endif
                @if($item->type == 'BUG') badge-danger @endif
              ">
              {{ $item->type }}</span> <span class="badge badge-secondary">{{ $item->theme }}</span>
              <div class="float-right">
                {{ $item->voteUp() }} <a href="/lab/{{ $item->id }}/vote?up=1"><i class="fas fa-thumbs-up"></i></a>
                {{ $item->voteDown() }} <a href="/lab/{{ $item->id }}/vote?down=1" class="text-danger"><i class="fas fa-thumbs-down"></i></a>
              </div>
            </div>
            <div class="card-body"> 
              {{ $item->body }}
            </div>
            <div class="card-footer"> 
              <small>Posté par <a href="/profils/{{ $item->user->name }}">{{ $item->user->name }}</a> le {{ $item->created_at->format('d/m/Y à H:i') }}</small>
            </div>
          </div>

        @endforeach

      @endif
    </div>

    <div class="tab-pane" id="planned" role="tabpanel" aria-labelledby="planned-tab">

    </div>

    <div class="tab-pane" id="opened" role="tabpanel" aria-labelledby="opened-tab">
      
    </div>

    <div class="tab-pane" id="closed" role="tabpanel" aria-labelledby="closed-tab">
      
    </div>

  </div>

</section>


@endsection