@extends('layouts.app')

@section('content')

@section('class', 'authentication edit')

<div class="col-sm-8 col-lg-6">  
    <header class="row justify-content-center">
        <h1><span>Riverbend Gardens</span> {{ __('Login') }}</h1>
        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
    </header>  

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <ul class="card-list">
            <li class="card">
                <label for="email">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </li>
            <li class="card">
                <label for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </li>
            <li class="card">
                <strong><input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} /> </strong>
                <label for="remember">
                    {{ __('Remember Me') }}
                </label>
            </li>

            <button type="submit" class="button save">
                {{ __('Login') }}
            </button>

            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </ul>
    </form>
    <footer></footer>
</div>
@endsection
