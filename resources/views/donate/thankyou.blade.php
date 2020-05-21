@extends('layouts.app')

@section('title', 'Merci!!!')
@section('description', 'Merci de soutenir blinest.com')

@section('content')

  <header class="masthead bg-primary text-white text-center">
      <div class="container d-flex align-items-center flex-column">

        <!-- Masthead Heading -->
        <h1 class="masthead-heading text-uppercase mb-0">Merci ♥</h1>

        <p>Grâce à toi le site va pouvoir continuer d'exister, d'évoluer et de s'améliorer!</p>

      </div>
  </header>

  <section class="pt-5">

    <div class="container">

      <div class="row d-flex justify-content-center">

        <div class="col-md-6 text-center mb-4">

          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif

          <p>Un reçu va arriver dans ta boite mail.</p>

          <a href="/" class="btn btn-success">Retourner aux parties</a>

        </div>

      </div>

    </div>
    
  </section>


@endsection