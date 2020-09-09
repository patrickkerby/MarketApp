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
            @if($market_day->estimated_revenue)
                <li><strong>Estimated Revenue:</strong> ${{ $market_day->estimated_revenue }}</li>
            @endif
            @if($market_day->actual_revenue)
                <li><strong>Actual Revenue:</strong> ${{ $market_day->actual_revenue }}</li>
            @endif
            @if($market_day->weather)
                <li><strong>Temperature:</strong> {{ $market_day->weather }}&#176;C</li>            
            @endif
            @if($market_day->wind)
                <li><strong>Wind gust speed:</strong> {{ $market_day->wind * 3.6 }} km/h</li>            
            @endif
        </ul>

        <table>                        
            @if($market_day->state == 'Returned' || $market_day->state == 'Completed' )
                <thead>
                    <tr>
                        <th>Products</th>
                        <th>Packed</th>
                        <th>Sold</th>
                        <th>~$</th>
                    </tr>
                </thead>
                <tbody> 
                @foreach($product_quantities as $item)
                    <tr>
                        <td>{{ $item->products->name }}</td>
                        <td>{{ $item->packed + 0 }}</td>
                        <td>{{ $item->packed - $item->returned }}</td>
                        <td>${{ $item->products->price * ($item->packed - $item->returned) }}</td>
                    </tr>
                @endforeach
            @else
                <thead>
                    <tr>
                        <th>Products</th>
                        <th>
                            @if($market_day->state == 'Draft' || $market_day->state == 'Ready To Pack' ) 
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
        <a class="util notes_trigger @if($has_notes)has_notes @endif" data-toggle="collapse" href="#notes" role="button" aria-expanded="false" aria-controls="notes">
                <i class="far fa-comment-dots"></i>
            </a>
            <a class="util options_trigger" data-toggle="collapse" href="#market_day_options" role="button" aria-expanded="false" aria-controls="market_day_options">
                <i class="fas fa-cogs"></i>
            </a>
            <div class="collapse" id="market_day_options">
                <button class="print-window">
                    <i class="fas fa-print"></i> Print
                </button>
                <form method="POST" action="/market_days/{{ $market_day->id }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit"><i class="far fa-trash-alt"></i> Delete</button>
                </form>
            </div>
            
            <a class="button" href="/market_days/{{$market_day->id}}/edit"><i class="far fa-edit"></i> Edit</a>

        </footer>
        <div class="notes collapse row no-gutters justify-content-center" id="notes">
            @if($market_day->admin_notes)
                <div class="col-sm-8">
                    <strong>Admin Notes:</strong>
                    <p>{{ $market_day->admin_notes }}</p>
                </div> 
            @endif                   
            @if($market_day->packing_notes)
                <div class="col-sm-8">
                    <strong>Packing Notes:</strong>
                    <p>{{ $market_day->packing_notes }}</p>
                </div>
            @endif    
            @if($market_day->market_notes)
                <div class="col-sm-8">
                    <strong>Market Notes:</strong>
                    <p>{{ $market_day->market_notes }}</p>
                </div>
            @endif 
        </div>
@endsection