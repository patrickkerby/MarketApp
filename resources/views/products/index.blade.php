@extends('layout')

@section('content')

  <div class="col-11 col-sm-8 col-lg-6">
    <header class="row justify-content-center">
      <h1>Products</h1>
      <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
    </header> 

      @foreach($categorized_products as $key => $item)


        <h2>{{$key}}:</h2>
        <ul class="card-list">
          @foreach($item as $item)
              <li class="card">
                <a href="/products/{{ $item->id }}/edit">
                  <label>{{ $item->name }}:</label>
                  <span class="setInput">${{ $item->price }}</span>
                  <span class="setInput"><i class="far fa-edit"></i></span>
                </a>
              </li>          
          @endforeach
        </ul>
      @endforeach

  </div>
  <footer>
    <a href="/products/create" class="button">Add new product</a>
  </footer>
@endsection