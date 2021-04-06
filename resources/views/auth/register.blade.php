@extends('layouts.app')

@section('title')
  Inscription blinest.com
@endsection

@section('description')
  Les inscriptions permettent de choisir un pseudonyme, de créer des playlists privées et d'éditer les jeux existants en ajoutant ou supprimant des extraits.
@endsection

@section('content')
  <header class="masthead bg-primary text-white text-center">
      <div class="container d-flex align-items-center flex-column">

        <!-- Masthead Heading -->
        <h1 class="masthead-heading text-uppercase mb-0">Inscription</h1>

        <!-- Icon Divider -->
        <div class="divider-custom divider-light">
          <div class="divider-custom-line"></div>
          <div class="divider-custom-icon">
            <i class="fas fa-star"></i>
          </div>
          <div class="divider-custom-line"></div>
        </div>

      </div>
  </header>

  <section class="page-section">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">

                    @if (session('message'))
                        <div class="alert alert-danger">{{ session('message') }}</div>
                    @endif


                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <a href="{{ url('/auth/redirect/facebook') }}" class="btn btn-primary"><i class="fab fa-facebook"></i> Se connecter via Facebook</a>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <a href="{{ url('/auth/redirect/discord') }}" class="btn btn-primary"><i class="fab fa-discord"></i> Se connecter via Discord</a>
                            </div>
                        </div>

                        <hr class="border-b border-primary">

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nom d'utilisateur</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                <small id="nameHelp" class="form-text text-muted">Le nom qui s'affichera dans les parties avec les autres joueurs.</small>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>


                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                <small id="emailHelp" class="form-text text-muted">Nous ne partagerons jamais votre adresse email avec personne.</small>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                <small id="passwordHelp" class="form-text text-muted">Conseil : Minimum 8 caractères, Majuscules, Minuscules, Caractères spéciaux, Chiffres.</small>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
