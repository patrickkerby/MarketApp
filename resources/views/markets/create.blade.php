@extends('layout')

@section('content')
    <div id="wrapper">
      <div id="page" class="container">
        <h2>New Market</h2>
        <form method="POST" action="/markets">
          @csrf
          
          <div class="field">
            <label class="label" for="name">Name</label>

            <div class="control">
              <input class="input" type="text" name="name" id="name" value="{{ old('name') }}" />
              @error('name')
                <p class="error">{{ $errors->first('name') }}</p>
              @enderror
            </div>
          </div>

          <div class="field">
            <label class="label" for="sort_order">Sort Order</label>

            <div class="control">
              <input class="input" type="number" name="sort_order" id="sort_order" value="{{ old('sort_order') }}" />
              @error('sort_order')
                <p class="error">{{ $errors->first('sort_order') }}</p>
              @enderror
            </div>
          </div>

          <div class="field">
            <label class="label" for="city">City</label>

            <div class="control">
              <input class="input" type="text" name="city" id="city" value="{{ old('city') }}"  />
              @error('city')
                <p class="error">{{ $errors->first('city') }}</p>
              @enderror
            </div>
          </div>

          <div class="field">
            <label class="label" for="postal_code">Postal Code</label>

            <div class="control">
              <input class="input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"  />
              @error('postal_code')
                <p class="error">{{ $errors->first('postal_code') }}</p>
              @enderror
            </div>
          </div>

          <div class="field">
            <label class="label" for="province">Province</label>

            <div class="control">
              <input class="input" type="text" name="province" id="province" value="{{ old('province') }}"  />
              @error('province')
                <p class="error">{{ $errors->first('province') }}</p>
              @enderror
            </div>
          </div>

          <div class="field">
            <label class="label" for="street_address">Street Address</label>

            <div class="control">
              <input class="input" type="text" name="street_address" id="street_address" value="{{ old('street_address') }}"  />
              @error('street_address')
                <p class="error">{{ $errors->first('street_address') }}</p>
              @enderror
            </div>
          </div>
          
          <div class="field">
            <div class="control">
              <button class="button main-action" type="submit">Create Market</button>
            </div>

          </div>
          
        </form>
      </div>
    </div>
@endsection