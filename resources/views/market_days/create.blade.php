@extends('layout')

@section('content')
    <div id="wrapper">
      <div id="page" class="container">
        <h2>New Market Day</h2>
        <form method="POST">
          @csrf

          <div class="field">
            <h3>Select your Markets:</h3>
            <div class="control">              
                @foreach($markets as $market)
                  <input type="checkbox" name="market[]" value="{{ $market->name }}" id="market-{{ $market->id }}" /> <label for="market-{{ $market->id }}">{{ $market->name }}</label><br>
                @endforeach
            </div>
          </div>

          <div class="field">
            <h3>Select your Products:</h3>
            <div class="control">              
                @foreach($products as $product)
                  <input type="checkbox" name="product[]" value="{{ $product->name }}" id="product-{{ $product->id }}" /> <label for="product-{{ $product->id }}">{{ $product->name }}</label><br>
                @endforeach
            </div>
          </div>          

          <div class="field">
            <div class="control">
              <button class="button" type="submit">Create Market Days</button>
            </div>

          </div>
          
        </form>

      @dump($_POST)
      </div>
    </div>
@endsection