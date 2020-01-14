@extends('layouts.admin')

@section('content')

<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">

      <!-- Masthead Heading -->
      <h1 class="masthead-heading text-uppercase mb-0">Tracks</h1>
      <p class="masthead-subheading font-weight-light mb-0">{{ $tracks_count }} Tracks</p>

    </div>
</header>

<section class="page-section">

  <div class="container-fluid">

    <admin-tracks></admin-tracks>

  </div>

</section>

@endsection