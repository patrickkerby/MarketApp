@extends('layout')

@section('content')
    <div id="wrapper">
      <div id="page" class="container">
        <h2>Edit Market</h2>
        <form method="POST" action="/markets/{{ $market->id }}">
          @csrf
          @method('PUT')
          
          <div class="field">
            <label class="label" for="name">Name</label>

            <div class="control">
              <input class="input" type="text" name="name" id="name" value="{{ $market->name }}" />
            </div>
          </div>

          <div class="field">
            <label class="label" for="sort_order">Sort Order</label>

            <div class="control">
              <input class="input" type="number" name="sort_order" id="sort_order" value="{{ $market->sort_order }}" />
              @error('sort_order')
                <p class="error">{{ $errors->first('sort_order') }}</p>
              @enderror
            </div>
          </div>

          <div class="field">
            <label class="label" for="price">City</label>

            <div class="control">
              <input class="input" type="text" name="city" id="city" value="{{ $market->city }}"  />
            </div>
          </div>

          <div class="field">
            <label class="label" for="postal_code">Postal Code</label>

            <div class="control">
              <input class="input" type="text" name="postal_code" id="postal_code" value="{{ $market->postal_code }}"  />
            </div>
          </div>

          <div class="field">
            <label class="label" for="province">Province</label>

            <div class="control">
              <input class="input" type="text" name="province" id="province" value="{{ $market->province }}"  />
            </div>
          </div>

          <div class="field">
            <label class="label" for="street_address">Street Address</label>

            <div class="control">
              <input class="input" type="text" name="street_address" id="street_address" value="{{ $market->street_address }}"  />
            </div>
          </div>

          <div class="field">
            <div class="control">
              <button class="button" type="submit">Update Product</button>
            </div>

          </div>
          
        </form>
      </div>
    </div>
@endsection