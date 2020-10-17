@extends('layouts.app')

@section('content')

@section('class', 'authentication edit')


<div class="col-sm-8 col-lg-6">  
    <header class="row justify-content-center">
        <h1><span>Riverbend Gardens</span> {{ __('Register') }}</h1>
        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
    </header>   
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <ul class="card-list">
            <li class="card">
                <label for="name" class="">{{ __('Name') }}</label>
                <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </li>            
            <li class="card">
                <label for="email" class="">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </li>
            <li class="card">
                <label for="password" class="">{{ __('Password') }}</label>
                <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </li>
            <li class="card">
                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="" name="password_confirmation" required autocomplete="new-password">
            </li>
        
        <button type="submit" class="save button">
            {{ __('Register') }}
        </button>
        
    </form>
    <footer>

    </footer>
</div>

@endsection
