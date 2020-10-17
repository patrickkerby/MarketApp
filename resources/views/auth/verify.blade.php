@extends('layouts.app')

@section('class', 'authentication edit')

<div class="col-sm-8 col-lg-6">  
    <header class="row justify-content-center">
        <h1><span>Riverbend Gardens</span> {{ __('Verify Your Email Address') }}</h1>
        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
    </header> 
    
    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    {{ __('Before proceeding, please check your email for a verification link.') }}
    {{ __('If you did not receive the email') }},
    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
    </form>
</div>
@endsection
