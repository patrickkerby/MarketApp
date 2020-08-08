@extends('layout')

@section('content')
    <div id="wrapper">
      <div id="page" class="container">
        <h2>New Market Day</h2>
        @dump($data)
        <form method="POST">
          @csrf
          <div class="field">
            <h3>Select your Markets:</h3>
              @foreach($markets as $market)
                <div class="marketInput">              
                  <input type="checkbox" name="market[{{ $market['id'] }}][name]" value="{{ $market['name'] }}" id="market-{{ $market->id }}" @isset($markets_session) @isset($markets_session[$market->id]) checked @endisset @endisset /> <label for="market-{{ $market->id }}">{{ $market->name }}</label>
                    @isset($markets_session[$market->id])
                      <input type="hidden" name="market[{{ $market['id'] }}][id]" value="{{ $market['id'] }}" />
                      <input type="hidden" name="market[{{ $market['id'] }}][admin_notes]" value="{{ $markets_session[$market->id]['admin_notes'] ?? '' }}" />
                      <input type="hidden" name="market[{{ $market['id'] }}][date]" value= "{{ $markets_session[$market->id]['date'] ?? '' }}" />                      
                    @endisset
                  <br>  
                </div>
              @endforeach
          </div>

          <div class="field">
            <h3>Select your Products:</h3>
            <div class="control">              

                @foreach($categorized_products as $key => $item)
                  <h3>{{$key}}:</h3>                  
                    @foreach($item as $item)
                      <input type="checkbox" name="product[]" value="{{ $item->id }}" id="product-{{ $item->id }}" @if($products_session && in_array($item->id, $products_session)) checked @endif /> <label for="product-{{ $item->id }}">{{ $item->name }}</label><br>
                    @endforeach
                @endforeach

            </div>
          </div>          

          @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
          @endif

          <div class="field">
            <div class="control">
              <button class="button" type="submit">+ Add Packing Quantities</button>
            </div>

          </div>
          
        </form>

      </div>
    </div>

    @endsection
