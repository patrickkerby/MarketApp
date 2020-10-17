@extends('layout')

@section('class', 'setup')

@section('content')
  <div class="col-11 col-sm-9 col-lg-10">
    <header class="row justify-content-center">
      <h1><span>Setup for</span> New Market Days</h1>
    </header>
        
        @if ($markets && $products)
          <p>Assign quantities for each product, per market</p>          
          <form method="POST">
            @csrf

            <section class="products">
              <table class="create-markets-table">
                <thead>
                  <tr>
                    @foreach($markets as $key => $market)            
                      @isset($market['name'])
                      <td>
                        <h3>{{ $market['name'] }}</h3>
                        <div class="dateInput">
                          <input type="date" name="market[{{ $key }}][date]" required @isset($market['date']) value="{{ $market['date'] }}" @endisset />
                          <input type="hidden" name="market[{{ $key }}][name]" value="{{ $market['name'] }}" />
                          <input type="hidden" name="market[{{ $key }}][market_id]" value="{{ $key }}" />
                          <input type="hidden" name="market[{{ $key }}][state]" value="1" />                        
                        </div>
                      </td>
                      @endisset
                      @php 
                        $market_count = $loop->count;
                      @endphp
                    @endforeach
                  </tr>
                  @error('market.date')
                    <tr>
                      <td><p class="error">{{ $errors->first('market.date') }}</p></td>
                    </tr>    
                  @enderror
                  
                </thead>
                <tbody>
                  @php
                      $i = -1;
                  @endphp
                  @foreach($products as $product)
                    <tr class="product-head">
                      <td colspan="{{ $market_count }}">{{ $product->name }}</td>
                    </tr>
                    <tr class="inputs card">
                      @foreach ($markets as $key => $market)
                        @isset($market['name'])
                        @php
                            $i++;
                            $prod_count_key = $key . $product->id;
                        @endphp
                          <td><input name="product_quantities[{{ $prod_count_key }}][packed]" type="number" placeholder="-" step="0.25" max="60" @isset($product_quantities[$prod_count_key]['packed']) value="{{ $product_quantities[$prod_count_key]['packed'] }}" @endisset /></td>
                          <input type="hidden" name="product_quantities[{{ $prod_count_key }}][product_id]" value="{{ $product->id }}" />                        
                          <input type="hidden" name="product_quantities[{{ $prod_count_key }}][market_id]" value="{{ $key }}" />                        
                        @endisset
                      @endforeach
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </section>
            <section class="notes">     
              <h3>Market Notes:</h3>
              @foreach ($markets as $key => $market)
                @isset($market['name'])
                  <div class="cardlist card large">
                    <label>{{ $market['name'] }}: Notes</label>
                    <textarea name="market[{{ $key }}][admin_notes]" class="" rows="8">@isset($market['admin_notes']){{ $market['admin_notes'] }}@endisset</textarea>
                  </div>
                @endisset
              @endforeach
            </section>
            
            <section>
              <button class="button save" type="submit" name="action" value="publish">Create Market Days</button>
            </section>            
            
            <footer>
                <a class="util options_trigger" data-toggle="collapse" href="#market_day_options" role="button" aria-expanded="false" aria-controls="market_day_options">
                    <i class="fas fa-cogs"></i>
                </a>
                
                <div class="collapse" id="market_day_options">
                  <button class="print-window revert">
                    <i class="fas fa-print"></i> Print
                  </button>
                  <button class="cancel revert" type="submit" name="action" value="cancel">Cancel</button>
                  <button class="revert" type="submit" name="action" value="save">Save Draft</button>
                  <a href="create-setup" class="edit"><i class="far fa-edit"></i> Edit</a>
                </div>
            </footer>
          </form>
        @else
          Whoops, you need to select your Markets and Products first.<br> <a href="create-setup">Click here to go back and and them.</a>
        @endif
  </div> 
@endsection