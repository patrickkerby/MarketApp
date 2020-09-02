@extends('layout')

@section('content')

@section('class', 'show')


{{-- {{ $currentWeatherInEdmonton }} --}}

    <div class="col-sm-8 col-lg-6">  
        <header class="row justify-content-center">
            <h1>
                <span>{{ $market_day->state }}</span>
                {{ $market_day->market->name }}                
            </h1>
            <div class="date">
                <span class="month">
                    {{ \Carbon\Carbon::parse($market_day->date)->format('F')}}
                </span>
                <span class="day">
                    {{ \Carbon\Carbon::parse($market_day->date)->format('j')}}
                </span>
                <span class="year">
                    {{ \Carbon\Carbon::parse($market_day->date)->format('Y')}}
                </span>
                
            </div>
        </header>      
        
        <ul>
            @if($market_day->employee)
                <li><strong>Employee:</strong> {{ $market_day->employee }}</li>
            @endif                
            @if($market_day->packing_notes)
                <li><strong>Packing Notes:</strong> {{ $market_day->packing_notes }}</li>
            @endif
            @if($market_day->market_notes)
                <li><stifrong>Market Notes:</strong> {{ $market_day->market_notes }}</li>
            @endif
            @if($market_day->admin_notes)
                <li><strong>Admin Notes:</strong> {{ $market_day->admin_notes }}</li>
            @endif
            @if($market_day->estimated_revenue)
                <li><strong>Estimated Revenue:</strong> {{ $market_day->estimated_revenue }}</li>
            @endif
            @if($market_day->actual_revenue)
                <li><strong>Actual Revenue:</strong> {{ $market_day->actual_revenue }}</li>
            @endif
            @if($market_day->weather)
                <li><strong>Temperature:</strong> {{ $market_day->weather }}&#176;C</li>            
            @endif
            @if($market_day->wind)
                <li><strong>Wind gust speed:</strong> {{ $market_day->wind * 3.6 }} km/h</li>            
            @endif
        </ul>

        <table>                        
            @if($market_day->state >= 3)
                <thead>
                    <tr>
                        <th>Products</th>
                        <th>Packed</th>
                        <th>Unsold</th>
                        <th>Sold</th>
                        <th>~$</th>
                    </tr>
                </thead>
                <tbody> 
                @foreach($product_quantities as $item)
                    <tr>
                        <td>{{ $item->products->name }}</td>
                        <td>{{ $item->packed + 0 }}</td>
                        <td>{{ $item->returned + 0 }}</td>
                        <td>{{ $item->packed - $item->returned }}</td>
                        <td>${{ $item->products->price * ($item->packed - $item->returned) }}</td>
                    </tr>
                @endforeach
            @else
                <thead>
                    <tr>
                        <th>Products</th>
                        <th>
                            @if($market_day->state <= 1)
                                Packing List
                            @else
                                Packed
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody> 
                @foreach($product_quantities as $item)
                    <tr>
                        <td>{{ $item->products->name }}</td>
                        <td>{{ $item->packed }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <footer>
        <a href="/market_days/{{$market_day->id}}/edit">Edit</a>
    </footer>
@endsection