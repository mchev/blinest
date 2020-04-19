@extends('layouts.app')

@section('title')
  Blind-Test - {{ $game->title }}
@endsection

@section('description')
  Blind-Test - {{ $game->title }}. {{ $game->description }}
@endsection

@section('content')

  <section class="custom-game bg-primary">

	<header class="row text-white text-center my-5">

		<div class="col-md-12">

		    <h1 class="masthead-heading text-uppercase mb-0">Blind-Test {{ $game->title }}</h1>

		    <p>Cette partie est protégée par un mot de passe.</p>

		    <div class="container d-flex justify-content-center my-4">

		    	<form method="post" action="{{ route('games.custom.password.check', $game) }}">

		    		@csrf
		    		@method('POST')

		    		<input type="password" name="password" class="form-control mb-4" placeholder="Mot de passe?">

		    		<button type="submit" class="btn btn-info btn-lg"><i class="fas fa-unlock-alt"></i> Sésame, ouvre-toi</button>

		    	</form>

		    </div>

		</div>

	</header>

  </section>

@endsection