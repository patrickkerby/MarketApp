@extends('layout')

@section('class', 'show')

@section('content')
      <div class="col-11 col-sm-8 col-lg-6">
        <header class="row justify-content-center">
          <h1>Edit Market</h1>
          <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
        </header> 
        <form method="POST" class="edit" action="/markets/{{ $market->id }}">
          @csrf
          @method('PUT')
          
          <ul class="card-list">
            <li class="card">
              <label for="name">Name</label>
              <input type="text" name="name" id="name" value="{{ $market->name }}" />
            </li>

            <li class="card">
              <label for="sort_order">Sort Order</label>
              <input type="number" name="sort_order" id="sort_order" value="{{ $market->sort_order }}" />
              @error('sort_order')
                <p class="error">{{ $errors->first('sort_order') }}</p>
              @enderror
            </li>

            <li class="card">
              <label for="price">City</label>
              <input type="text" name="city" id="city" value="{{ $market->city }}"  />
            </li>

            <li class="card">
              <label for="postal_code">Postal Code</label>
              <input type="text" name="postal_code" id="postal_code" value="{{ $market->postal_code }}"  />
            </li>

            <li class="card">
              <label for="province">Province</label>
              <input type="text" name="province" id="province" value="{{ $market->province }}"  />
            </li>

            <li class="card">
              <label class="label" for="street_address">Street Address</label>
              <input class="input" type="text" name="street_address" id="street_address" value="{{ $market->street_address }}"  />
            </li>
          </ul>
          
          <button class="button main-action" type="submit">Update Market</button>
          
        </form>
</div>
<footer></footer>
@endsection