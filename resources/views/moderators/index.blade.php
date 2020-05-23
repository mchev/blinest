@extends('layouts.app')

@section('title')
  Blind-Test - Événements
@endsection

@section('description')
  Tous les événements blind-test. Dans la vrai vie ou en parties privées sur blinest.
@endsection

@section('content')

  <section class="page-events">

  	<div class="container-fluid">

  		<div class="row">

  			<div class="col-md-3 bg-secondary">

  				<ul class="list-group">
	  				@foreach($games as $game)
						<li href="#" class="list-group-item list-group-item-action flex-column align-items-start">
							<div class="d-flex w-100 justify-content-between">
		  						<h5>{{ $game->title }}</h5>
		  						<small class="text-muted">{{ $game->tracks->count() }} extraits<br>{{ $game->hit }} scores</small>
		  					</div>
		  					@foreach($game->moderators as $moderator)
		  						<span class="badge badge-info">{{ $moderator->name}}</span>
		  					@endforeach
		  				</li>
	  				@endforeach
	  			</ul>

  			</div>

  			<div class="col-md-6">

  				<h2>Tableau de bord</h2>

  				<div class="card bg-info text-white">

  					<div class="card-header">
  						<h4>FAQ</h4>
  					</div>

  					<div class="card-body">

		  				<h5>C'est quoi les réponses personnalisées?</h5>
		  				<p>Les réponses personnalisées sont utiles uniquement lorsque l'on souhaite retrouver le titre d'un film, d'une série, d'une réponse spécifique. Il ne faut pas les utiliser pour les parties généralistes car la réponse est valable pour un point.</p>

		  				<h5>A quoi correspondent les scores?</h5>
		  				<p>Un score est enregistré lorsqu'une partie est jouée jusqu'à la fin par un utilisateur dont le score final n'est pas égal à zéro.</p>

		  			</div>

		  		</div>

  				<ul>
  					<li>Bloquer un utilisateur</li>
  					<li>Afficher le track module</li>
  					<li>Chat</li>
  				</ul>

  			</div>

  			<div class="col-md-3 bg-secondary">
  				<ul class="list-group">
	  				@foreach($games as $game)
						<li href="#" class="list-group-item list-group-item-action flex-column align-items-start">
		  					@foreach($game->moderators as $moderator)
		  						@if(auth()->user()->id == $moderator->id)
		  							<h5>{{ $game->title }}</h5>
		  							<button class="btn btn-info">Editer les extraits</button>
		  						@endif
		  					@endforeach
		  				</li>
	  				@endforeach
	  			</ul>
  			</div>

  		</div>

  	</div>
    
  </section>

@endsection