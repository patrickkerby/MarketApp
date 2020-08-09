@extends('layout')

@section('content')
    <div class="content">
        @dump($product_quantities)
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
        <table>
            <thead>
                <tr>
                    <td>Product</td>
                    <td>Packed</td>
                    <td>Market</td>
                </tr>
            </thead>
            <tbody>
        @foreach($product_quantities as $item)
            <tr>
                <td>Vegetable: {{ $item->product_id }}</td>
                <td><strong>Packed:</strong> {{ $item->packed }}</td>
                <td>{{ $item->market_day_id }}</td>
            </tr>
        @endforeach
            </tbody>
        </table>
    </div>
@endsection