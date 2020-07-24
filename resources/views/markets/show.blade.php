@extends('layout')

@section('content')
    <div class="content">
        <div class="title m-b-md">
            <h2>{{ $market->name }}</h2>
        </div>
        <h3>
          {{ $market->street_address }}, {{ $market->city }} {{ $market->province }}, {{ $market->postal_code }}
        </h3>                
    </div>
@endsection