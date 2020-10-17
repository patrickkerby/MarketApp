@extends('layout')

@section('content')

@section('class', 'home')

    <div class="col-sm-8 col-lg-6">
      <header class="row justify-content-center">
        <h1><span>Riverbend Gardens</span> Market Days</h1>
        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
      </header>

      @can ('view_completed_market_days')
        <h1>TEST</h1>
      @endcan

      
      <div class="row no-gutters">
        <h2 class="col-12">
          Completed
        </h2>
        <ul class="card-list">
          @foreach ($market_days as $item)
            <li class="col-sm-12 card">
              <a href="/market_days/{{ $item->id }}/edit">
                <strong>{{ \Carbon\Carbon::parse($item->date)->format('F j, Y')}}</strong>
                {{ $item->market->name }}
                <span class="actual_revenue"> - ${{ $item->actual_revenue }}</span>
                <i class="fas fa-chevron-right"></i>
              </a>
            </li>  
          @endforeach              
        </ul>
      </div>

       {{-- @can ('view_completed_market_days')
       <div class="row no-gutters">
        <h2 class="col-12">Completed</h2>
        <ul class="card-list">
          @foreach($items as $item)
          <li class="col-sm-12 card">
            <a href="/market_days/{{ $item->id }}/edit">
              <strong>{{ \Carbon\Carbon::parse($item->date)->format('F j, Y')}}</strong>
              {{ $item->market->name }}
              <i class="fas fa-chevron-right"></i>
            </a>
          </li>  
          @endforeach
        </ul>
      </div>
      @endcan --}}

      <div>
        <a href="/market_days/" class="button">Back to all Market Days</a>
      </div>
      <footer></footer>
    </div>
@endsection


