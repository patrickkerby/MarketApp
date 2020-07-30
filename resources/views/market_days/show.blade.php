@extends('layout')

@section('content')
    <div class="content">
        <h2>{{ $markets->name }} ({{ $market_day->date }})</h2>
        <ul>
            <li><strong>Date:</strong> {{ $market_day->date }}</li>
            <li><strong>Employee:</strong> {{ $market_day->employee }}</li>
            <li><strong>Packing Notes:</strong> {{ $market_day->packing_notes }}</li>
            <li><strong>Market Notes:</strong> {{ $market_day->market_notes }}</li>
            <li><strong>Admin Notes:</strong> {{ $market_day->admin_notes }}</li>
            <li><strong>Estimated Revenue:</strong> {{ $market_day->estimated_revenue }}</li>
            <li><strong>Actual Revenue:</strong> {{ $market_day->actual_revenue }}</li>
            <li><strong>State:</strong> {{ $market_day->state }}</li>
        </ul>
        
    </div>
@endsection