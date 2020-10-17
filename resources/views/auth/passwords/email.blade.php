@extends('layouts.app')

@section('content')


@section('class', 'authentication edit')

<div class="col-sm-8 col-lg-6">  
    <header class="row justify-content-center">
        <h1><span>Riverbend Gardens</span> {{ __('Reset Password') }}</h1>
        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
    </header> 

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
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
        </ul>
        <button type="submit" class="btn btn-primary">
            {{ __('Send Password Reset Link') }}
        </button>
    </form>
    <footer></footer>
</div>
@endsection
