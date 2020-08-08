@extends('layout')

@section('content')
    <div>
      <a href="/products/create" class="button">Add new product</a>
    </div>
    <div class="">

      @foreach($categorized_products as $key => $item)

      {{-- @dump($item) --}}
        <h3>{{$key}}:</h3>
        <ul>
          @foreach($item as $item)
              <li><a href="/products/{{ $item->id }}"><strong>{{ $item->name }}:</strong> ${{ $item->price }}</a></li>          
          @endforeach
        </ul>
      @endforeach
    </div>
@endsection