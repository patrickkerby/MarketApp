@extends('layouts.app')

@section('content')

@section('class', 'authentication edit')

<div class="col-sm-8 col-lg-6">  
    <header class="row justify-content-center">
        <h1><span>Riverbend Gardens</span> {{ __('Reset Password') }}</h1>
        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
    </header> 

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <ul class="card-list">
            <li class="card">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </li>
            <li class="card">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </li>
            <li class="card">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
            </li>
        </ul>
        <button type="submit" class="btn btn-primary">
            {{ __('Reset Password') }}
        </button>
    </form>
    <footer></footer>
</div>
@endsection
