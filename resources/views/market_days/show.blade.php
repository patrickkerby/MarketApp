@extends('layout')

@section('content')

{{-- {{ $currentWeatherInEdmonton }} --}}

    <div class="content">        
        <h2>{{ $market_day->market->name }} ({{ $market_day->date }})</h2>
        <ul>
            <li><strong>Date:</strong> {{ $market_day->date }}</li>
            <li><strong>Employee:</strong> {{ $market_day->employee }}</li>
            <li><strong>Packing Notes:</strong> {{ $market_day->packing_notes }}</li>
            <li><strong>Market Notes:</strong> {{ $market_day->market_notes }}</li>
            <li><strong>Admin Notes:</strong> {{ $market_day->admin_notes }}</li>
            <li><strong>Estimated Revenue:</strong> {{ $market_day->estimated_revenue }}</li>
            <li><strong>Actual Revenue:</strong> {{ $market_day->actual_revenue }}</li>
            <li><strong>State:</strong> {{ $market_day->state }}</li>
            <li><strong>Temperature:</strong> {{ $market_day->weather }}&#176;C</li>            
            <li><strong>Wind gust speed:</strong> {{ $market_day->wind * 3.6 }} km/h</li>            
        </ul>

        <table> 
            <thead>
                <tr>
                    <th><h4>Product</h4></th>
                    <th><h4>Packed</h4></th>
                    <th><h4>Returned</h4></th>
                    <th><h4>Sold</h4></th>
                    <th><h4>~Revenue</h4></th>
                </tr>
            </thead>
            <tbody>
        @foreach($product_quantities as $item)
            <tr>
                <td>{{ $item->products->name }}</td>
                <td>{{ $item->packed }}</td>
                <td>{{ $item->returned }}</td>
                <td>{{ $item->packed - $item->returned }}</td>
                <td>${{ $item->products->price * ($item->packed - $item->returned) }}</td>
            </tr>
        @endforeach
            </tbody>
        </table>
        <a href="edit/">Edit</a>
    </div>
@endsection