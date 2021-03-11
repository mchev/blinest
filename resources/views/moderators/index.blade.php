@extends('layouts.app')

@section('title')
  Blind-Test - Modérateurs
@endsection

@section('description')
  La page dédiée aux modérateurs de blinest.
@endsection

@section('content')

  <section class="page-events">

  	<div class="container-fluid">

  		<div class="row">

  			<div class="col-lg-3 bg-secondary">

          <moderator-users></moderator-users>

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

  			<div class="col-lg-9">

  				<h2 class="text-center mb-4">Le salon des modérateurs</h2>

          <div class="card mb-3">
            <div class="card-header">
              <h4>Chat modérateurs</h4>
            </div>
            <div class="card-body">
              <moderator-chat></moderator-chat>
            </div>
          </div>

          <div class="card bg-info text-white mb-3">

            <div class="card-header">
              <h4>Demandes à traiter</h4>
            </div>

            <div class="card-body">
              <list-ticket></list-ticket>
            </div>

          </div>

  				<div class="card mb-4">

  					<div class="card-header">
  						<h4>FAQ</h4>
  					</div>

  					<div class="card-body">

		  				<h5>C'est quoi les réponses personnalisées?</h5>
		  				<p>Les réponses personnalisées sont utiles uniquement lorsque l'on souhaite retrouver le titre d'un film, d'une série, d'une réponse spécifique. Il ne faut pas les utiliser pour les parties généralistes car la réponse est valable pour un point.</p>

		  				<h5>A quoi correspondent les scores?</h5>
		  				<p>Un score est enregistré lorsqu'une partie est jouée jusqu'à la fin par un utilisateur dont le score final n'est pas égal à zéro.</p>

              <h5>Comment effacer un message sur le chat?</h5>
              <p>Pour être supprimé, un message doit obtenir trois pouces rouges de trois utilisateur.rice.s différent.e.s.</p>

              <h5>D'où viennent les demandes?</h5>
              <p>Sur chacune des pages correspondant à une partie, se trouve un micro-formulaire de contact (en dessous des podiums). Seul.e.s les utilisateur.rice.s connecté.e.s peuvent avoir accès à ce formulaire.</p>

              <h5>Pourquoi les évolutions sont si longues à venir?</h5>
              <p>Blinest est un site personnel qui est codé sur le temps libre. Le site n'est pas abandonné mais il peut arriver certaines periodes pendant lesquelles d'autres projets prennent le dessus.</p>

		  			</div>

		  		</div>

  			</div>

  		</div>

  	</div>
    
  </section>

@endsection