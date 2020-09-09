@extends('layout')

@section('content')

@section('class', 'home')

    <div class="col-sm-8 col-lg-6">
      <header class="row justify-content-center">
        <h1><span>Riverbend Gardens</span> Market Days</h1>
        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
      </header>

      @foreach ($market_days as $state => $items)

        @unless($state == 4)
        <div class="row no-gutters">
          <h2 class="col-12">
            @switch($state)
              @case(0)
                Draft
                @break
            
              @case(1)
                Ready To Pack
              @break
            
              @case(2)
                Packed
              @break
            
              @case(3)
                Returned
              @break
            
              @case(4)
                Completed
              @break
            @endswitch
          </h2>
          
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
        @endunless
      @endforeach

      <div>
        <a href="/market_days/create-setup" class="button">Add new market day</a>
      </div>
    </div>
@endsection


