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

          <h3 style="margin-top: 30px; margin-bottom: 15px;">Operational Costs</h3>
          <p style="color: #666; margin-bottom: 15px; font-size: 14px;">
            <em>Enter typical values per market day for labor, and annual totals for stall/other fees.</em>
          </p>
          <ul class="card-list">
            <li class="card">
              <label for="typical_employees">Typical # of Employees <small>(per market day)</small></label>
              <input type="number" name="typical_employees" id="typical_employees" min="0" step="1" value="{{ $market->typical_employees }}" />
            </li>

            <li class="card">
              <label for="typical_hours">Typical Hours <small>(per market day)</small></label>
              <input type="number" name="typical_hours" id="typical_hours" min="0" step="0.5" value="{{ $market->typical_hours }}" />
            </li>

            <li class="card">
              <label for="avg_wage">Average Wage <small>(per hour)</small></label>
              <div style="position: relative;">
                <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">$</span>
                <input type="number" name="avg_wage" id="avg_wage" min="0" step="0.01" value="{{ $market->avg_wage }}" style="padding-left: 25px;" />
              </div>
            </li>

            <li class="card">
              <label for="annual_stall_fee">Annual Stall/Booth Fees <small>(total for season/year)</small></label>
              <div style="position: relative;">
                <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">$</span>
                <input type="number" name="annual_stall_fee" id="annual_stall_fee" min="0" step="0.01" value="{{ $market->annual_stall_fee }}" style="padding-left: 25px;" />
              </div>
            </li>

            <li class="card">
              <label for="annual_other_fees">Annual Other Fees/Expenses <small>(total for season/year)</small></label>
              <div style="position: relative;">
                <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">$</span>
                <input type="number" name="annual_other_fees" id="annual_other_fees" min="0" step="0.01" value="{{ $market->annual_other_fees }}" style="padding-left: 25px;" />
              </div>
            </li>
          </ul>
          
          <button class="button main-action" type="submit">Update Market</button>
          
        </form>

        <form method="POST" action="/markets/{{ $market->id }}" style="margin-top: 20px;">
          @csrf
          @method('DELETE')
          <button type="submit" class="button" style="background-color: #dc3545; border-color: #dc3545;" onclick="return confirm('Are you sure you want to archive this market? Historical data will be preserved.')">
            Archive Market
          </button>
        </form>
</div>
<footer></footer>
@endsection