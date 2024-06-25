@extends('layout')

@section('content')

    <div class="col-11 col-sm-8 col-lg-6">
      <header class="row justify-content-center">
        <h1>Markets</h1>
        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
      </header>  

      <ul class="card-list">
        @foreach ($markets as $market)
          <li class="card">
            <a href="/markets/{{ $market->id }}/edit">
              <strong>{{ $market->sort_order }}</strong>
              {{ $market->name }}
              <i class="fas fa-chevron-right"></i>
            </a>
          </li>
        @endforeach
      </ul>
    </div>
    <footer>
      <a href="/markets/create" class="button main-action">Add new market</a>
    </footer>
    
@endsection