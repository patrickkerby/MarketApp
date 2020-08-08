@extends('layout')

@section('content')
    <div id="wrapper">
      <div id="page" class="container">
        <h2>Fill up your trucks!</h2>
        
        @dump($data)
        
        @if ($markets && $products)
          <p>Assign quantities for each product, per market</p>          
          <form method="POST">
            @csrf
            <div class="edit">
              <button class="button" type="submit" name="action" value="save">Save Draft</button>
              <a href="create-setup" class="button">Add / Remove Markets &amp; Products</a>
            </div>
            
            <table>
              <thead>
                <tr>
                  <td></td>
                  @foreach($markets as $key => $market)            
                    @isset($market['name'])
                    <td>
                      <strong>{{ $market['name'] }}</strong>
                      <br>
                      <div class="dateInput">
                        <input type="date" name="market[{{ $key }}][date]" @isset($market['date']) value="{{ $market['date'] }}" @endisset />
                        <input type="hidden" name="market[{{ $key }}][name]" value="{{ $market['name'] }}" />
                        <input type="hidden" name="market[{{ $key }}][market_id]" value="{{ $key }}" />
                        <input type="hidden" name="market[{{ $key }}][state]" value="0" />                        
                      </div>
                    </td>
                    @endisset
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
                  <tr>
                    <td><strong>{{ $product->name }}</strong></td>
                     
                    @foreach ($markets as $key => $market)
                      @isset($market['name'])
                      @php
                          $i++;
                          $prod_count_key = $key . $product->id;
                      @endphp
                        <td><input name="product_quantities[{{ $prod_count_key }}][packed]" type="number" step="0.25" max="60" @isset($product_quantities[$prod_count_key]['packed']) value="{{ $product_quantities[$prod_count_key]['packed'] }}" @endisset /></td>
                        <input type="hidden" name="product_quantities[{{ $prod_count_key }}][product_id]" value="{{ $product->id }}" />                        
                        <input type="hidden" name="product_quantities[{{ $prod_count_key }}][market_id]" value="{{ $key }}" />                        
                      @endisset
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
            <section class="notes">            
              @foreach ($markets as $key => $market)
                @isset($market['name'])
                  <div>
                    <strong>{{ $market['name'] }}: Notes</strong><br>
                    <textarea name="market[{{ $key }}][admin_notes]" class="" rows="8">@isset($market['admin_notes']){{ $market['admin_notes'] }}@endisset</textarea>
                  </div>
                @endisset
              @endforeach
            </section>
            <footer>
              <button class="cancel" type="submit" name="action" value="cancel">Cancel</button>
              <button class="button" type="submit" name="action" value="save">Save Draft</button>
              <button class="button" type="submit" name="action" value="publish">Create Market Days</button>
            </footer>

          </form>

        @else
          Whoops, you need to select your Markets and Products first.<br> <a href="create-setup">Click here to go back and and them.</a>
        @endif


      </div>
    </div>
@endsection