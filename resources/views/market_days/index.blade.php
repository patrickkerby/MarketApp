@extends('layout')

@section('content')
    <div>
      <a href="/market_days/create-setup" class="button">Add new market day</a>
    </div>
    <div class="content">


      @foreach ($market_days as $state => $items)

        @unless($state == 4)
          <h3>
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
          </h3>
          
          <ul>
              @foreach($items as $item)
              <li>
                <a href="/market_days/{{ $item->id }}"><strong>{{ $item->market->name }}</strong> - {{ $item->date }}</a>
              </li>  
              @endforeach
          </ul>
        @endunless
      @endforeach
    </div>
@endsection


