@extends('layouts.app')

@section('title', 'Contact - Blind-Test')
@section('description', 'Envoyez-nous vos commentaires, remarques, bugs et évolutions possibles.')

@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">Contactez-nous</h1>

      <!-- Icon Divider -->
      <div class="divider-custom divider-light">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon">
          <i class="fas fa-inbox"></i>
        </div>
        <div class="divider-custom-line"></div>
      </div>

      <!-- Masthead Subheading -->
      <p class="masthead-subheading font-weight-light mb-0">Envoyez-nous vos commentaires</p>

    </div>
</header>

<section class="page-section portfolio" id="portfolio">
    <div class="container">

      @if (\Session::has('message'))

        <div class="alert alert-success">
          {!! \Session::get('message') !!}
        </div>

      @endif

      <form action="{{ url('/send') }}" method="post">

        @csrf

        <div class="form-group">
          <label>Votre adresse email :</label>
          <input type="email" name="from" class="form-control" value="@if(Auth::check()) {{ Auth::user()->email }} @endif" required>
        </div>

        <div class="form-group">
          <label>Votre message :</label>
          <textarea name="message" class="form-control" required></textarea>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-lg btn-success">Envoyer</button>
        </div>

      </form>

    </div>
</section>


@endsection