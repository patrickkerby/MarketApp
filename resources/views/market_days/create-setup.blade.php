@extends('layout')

@section('content')

@section('class', 'setup')


<div class="col-11 col-sm-8 col-lg-6">
  <header class="row justify-content-center">
    <h1><span>Setup for</span> New Market Days</h1>
  </header>

        <form method="POST">
          @csrf
          <h2>Select your Markets:</h2>
          <ul class="card-list">
            @foreach($markets as $market)
              <li class="card half">
                  <strong><input type="checkbox" name="market[{{ $market['id'] }}][name]" value="{{ $market['name'] }}" id="market-{{ $market->id }}" @isset($markets_session) @isset($markets_session[$market->id]) checked @endisset @endisset /></strong>
                <label for="market-{{ $market->id }}">{{ $market->name }}</label>
                  @isset($markets_session[$market->id])
                    <input type="hidden" name="market[{{ $market['id'] }}][id]" value="{{ $market['id'] }}" />
                    <input type="hidden" name="market[{{ $market['id'] }}][admin_notes]" value="{{ $markets_session[$market->id]['admin_notes'] ?? '' }}" />
                    <input type="hidden" name="market[{{ $market['id'] }}][date]" value= "{{ $markets_session[$market->id]['date'] ?? '' }}" />                      
                  @endisset
              </li>
            @endforeach
          </ul>

          <h2>Select your Products:</h2>
          @foreach($categorized_products as $key => $item)
            <h3>{{$key}}:</h3>                  
              <ul class="card-list">
                @foreach($item as $item)
                  <li class="card">
                    <strong><input type="checkbox" name="product[]" value="{{ $item->id }}" id="product-{{ $item->id }}" @if($products_session && in_array($item->id, $products_session)) checked @endif /> </strong>
                    <label for="product-{{ $item->id }}">{{ $item->name }}</label>
                  </li>
                @endforeach
              </ul>
            @endforeach       

          @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif

          <footer>
            <section>
              <button class="button save" type="submit"><i class="fas fa-plus"></i> Add Packing Quantities</button>
            </section>
          </footer>
          
        </form>


    @endsection
