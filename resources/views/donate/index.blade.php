@extends('layouts.app')

@section('title', 'Faire un don')
@section('description', 'Ce site fait avec ♥ vous est proposé gratuitement. Si vous souhaitez le soutenir (serveur, maintenance, développement) vous pouvez faire un don.')

@section('content')

  <header class="masthead bg-primary text-white text-center">
      <div class="container d-flex align-items-center flex-column">

        <!-- Masthead Heading -->
        <h1 class="masthead-heading text-uppercase mb-0">Soutenir Blinest</h1>

        <!-- Masthead Subheading -->
        <p class="masthead-subheading font-weight-light">Ce site fait avec ♥ vous est proposé gratuitement.<br>Vous pouvez le soutenir (serveur, maintenance, développement) en faisant un don sur cette page.</p>

      </div>
  </header>

  <section class="pt-5">

    <div class="container">

      <div class="row d-flex justify-content-center">

        <div class="col-md-6">

          <donate :error="{{ $error }}"></donate>

        </div>

      </div>

      <div class="row">

        <div class="col text-center">

          <p>Tu peux également faire un don via <a href="https://liberapay.com/Blinest/donate">Liberpay</a>.</p>

        </div>

      </div>

    </div>
    
  </section>

  <section class="bg-primary text-white py-5">

    <div class="container">

      <div class="row d-flex justify-content-center">

        <div class="col-md-6">

          <h2 class="mb-4">Je veux aider blinest autrement</h2>

          <h5>Animer des parties privées</h5>
          <p>Blinest s'appuie sur la communauté de joueurs pour faire vivre le site. La création et l'animation de parties privées permet de faire connaitre le site et de le rendre plus vivant.</p>

          <h5>Devenir modérateur</h5>
          <p>Si tu veux t'impliquer encore plus, il est possible de devenir modérateur. Il suffit d'<a class="text-dark" href="/contact">envoyer un mail</a> avec ton pseudo, la partie publique que tu aimerais aider à maintenir et quelques lignes sur tes motivations.</p>

          <h5>Développeur</h5>
          <p>Blinest est open source. Tout le monde peut participer au développement du site. Le code source est disponible sur <a target="_blank" class="text-dark" href="https://github.com/mchev/blinest">github</a>.</p>

        </div>

      </div>

    </div>

  </section>

@endsection