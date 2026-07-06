@extends('layout')

@section('content')

    <div class="col-11 col-sm-8 col-lg-6">
      <header class="row justify-content-center">
        <h1>Markets</h1>
        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
      </header>  

      <h2>Active Markets</h2>
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

      @if($archivedMarkets->count() > 0)
        <h2 style="margin-top: 40px;">Archived Markets</h2>
        <ul class="card-list">
          @foreach ($archivedMarkets as $market)
            <li class="card" style="opacity: 0.6;">
              <a href="/markets/{{ $market->id }}/edit">
                <strong>{{ $market->sort_order }}</strong>
                {{ $market->name }}
                <i class="fas fa-chevron-right"></i>
              </a>
              <form method="POST" action="{{ route('markets.restore', $market->id) }}" style="display: inline; margin-left: 10px;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #28a745; cursor: pointer;" title="Restore market">
                  <i class="fas fa-undo"></i>
                </button>
              </form>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
    <footer>
      <a href="/markets/create" class="button main-action">Add new market</a>
    </footer>
    
@endsection