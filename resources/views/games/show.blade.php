@extends('layouts.app')

@section('title')
	Blind-Test - {{ $game->title }}
@endsection

@section('description')
	Blind-Test - {{ $game->title }}. {{ $game->description }}
@endsection

@section('content')
<div class="container">

    <div class="row justify-content-center">

    	<div class="col-md-12">


    		<div class="row">

    			<div class="col-md-12 bg-primary text-white">

    				 @if(Auth::check())
    				  <a  class="my-2 btn btn-info" href="{{ route('games.edit', $game) }}">Editer</a>
    				  <a  class="my-2 btn btn-info" href="{{ route('games.private', $game) }}">Partie privée</a>
    				 @endif

    				 <game :game="{{ $game->toJson() }}"></game>

    			</div>

    		</div>

    		<div class="row mt-4">

    			<div class="col-lg-4">

		            <div class="card mb-2">

		            	<div class="card-header">

		            		<h5>Tu viens d'écouter</h5>

		            	</div>

		            	<div class="card-body">


		            	</div>


		            </div>


    			</div>

    			<div class="col-lg-4">

		            <div class="card mb-2">

		            	<div class="card-header">

		            		<h5>Classement</h5>

		            	</div>

		            	<div class="card-body">


		            	</div>


		            </div>


    			</div>

    			<div class="col-lg-4">

		            <div class="card mb-2">

		            	<div class="card-header">

		            		<h5>Salon {{ $game->title }}</h5>

		            	</div>

		            	<div class="card-body">


		            		@guest

		            			<div class="alert alert-info">Tu dois être <a href="{{ route('login') }}">connecté</a> pour utiliser le chat</div>

		            		@else

		            			<chat :game="{{ $game->toJson() }}" :user="{{ Auth::user()->toJson() }}"></chat>

		            		@endguest

		            	</div>


		            </div>


    			</div>

    		</div>

    	</div>
        
    </div>

</div>
@endsection