<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-label-group">

        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}" required>
        <label for="email">{{ __('E-Mail Address') }}</label>

        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

    </div>

    <hr>

    <div class="form-label-group">

        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" name="password" required>
        <label for="password">{{ __('Password') }}</label>

        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif

    </div>

    <button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">
        {{ __('Login') }}
    </button>

    @if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    @endif

    <hr class="my-4">
    
    <button class="btn btn-lg btn-google btn-block text-uppercase" type="submit"><i class="fab fa-google mr-2"></i> Connexion avec Google</button>
    <button class="btn btn-lg btn-facebook btn-block text-uppercase" type="submit"><i class="fab fa-facebook-f mr-2"></i> Connexion avec Facebook</button>

</form>