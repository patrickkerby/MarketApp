@extends('layout')

@section('content')
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
        <form method="POST" class="market_day_edit" action="/market_days/{{ $market_day->id }}">
            @csrf
            @method('PUT')                                    

            @switch($market_day->state)
                @case('Draft')
                    <ul class="card-list">
                        <li class="card"><label for="employee">Market Employee(s):</label> <input name="employee" id="employee" type="text" @isset($market_day->employee) value="{{ $market_day->employee }}" @endisset /></li>
                    </ul>  
                    <section class="products">
                        <div class="list-head">
                            <h4>Product</h4>
                            <h4>To Pack</h4>
                        </div>
                        <ul class="card-list">
                        @foreach($product_quantities as $item)
                            <li class="card">
                                <label for="item{{ $item->id }}">{{ $item->products->name }}</label>
                                <input name="packed[{{ $item->id }}]" id="item{{ $item->id }}" type="number" step="0.25" max="60" @isset($item->packed) value="{{ $item->packed + 0 }}" @endisset />
                            </li>
                        @endforeach
                        </ul>
                    </section>
                    <div class="cardlist card large">
                        <label for="admin_notes">Admin Notes:</label>
                        <textarea name="admin_notes" id="admin_notes" class="" rows="8">@isset($market_day->admin_notes){{ $market_day->admin_notes }}@endisset</textarea>
                    </div>
                    <input type="hidden" name="state" value="1" />
                    <button class="button" type="submit">Ready To Pack? <i class="fas fa-check"></i></button>
                    @break

                @case('Ready To Pack')
                    <div class="row justify-content-center">
                        <p class="col-9">This market is ready to be packed. If any product are unavailable, or any changes to the numbers below need to be made, adjust them before you continue.</p>
                    </div>
                    <ul class="card-list">
                        <li class="card"><label for="employee">Market Employee(s):</label> <input name="employee" id="employee" type="text" @isset($market_day->employee) value="{{ $market_day->employee }}" @endisset /></li>
                    </ul>  
                    <section class="products">
                        <div class="list-head">
                            <h4>Product</h4>
                            <h4>To Pack</h4>
                        </div>
                        <ul class="card-list">
                        @foreach($product_quantities as $item)
                            <li class="card">
                                <label for="item{{ $item->id }}">{{ $item->products->name }}</label>
                                <input name="packed[{{ $item->id }}]" id="item{{ $item->id }}" type="number" step="0.25" max="60" @isset($item->packed) value="{{ $item->packed + 0 }}" @endisset />
                            </li>
                        @endforeach
                        </ul>                        
                    </section>
                    <div class="cardlist card large">
                        <label for="packing_notes">Packing Notes:</label>
                        <textarea name="packing_notes" id="packing_notes" class="" rows="8" placeholder="Enter any relevant notes or comments for staff packing the trucks.">@isset($market_day->packing_notes){{ $market_day->packing_notes }}@endisset</textarea>
                    </div>
                    <input type="hidden" name="state" value="2" />
                    <button class="button" type="submit">This Market is packed and ready to go! <i class="fas fa-check"></i></button>
                    @break

                @case('Packed')
                    <div class="row justify-content-center">
                        <p class="col-9">Now that market is over, count how many of each product is leftover, and report it below!</p>
                    </div>
                    <ul class="card-list">
                        <li class="card"><label for="employee">Market Employee(s):</label> <input name="employee" id="employee" type="text" @isset($market_day->employee) value="{{ $market_day->employee }}" @endisset /></li>
                    </ul>  
                    <section class="products">
                        <div class="list-head">
                            <h4 class="product">Product</h4>
                            <h4 class="packed">Packed</h4>
                            <h4 class="returned">Unsold</h4>
                        </div>
                        <ul class="card-list">
                        @foreach($product_quantities as $item)
                        <li class="card">
                            <label for="item{{ $item->id }}">{{ $item->products->name }}</label>
                            <span class="setInput">{{ $item->packed }}</span>
                            <input name="returned[{{ $item->id }}]" id="item{{ $item->id }}" type="number" step="0.25" max="{{ $item->packed }}" @isset($item->returned)value="{{ $item->returned + 0 }}"@endisset />
                            </tr>
                        @endforeach
                            </tbody>
                        </table>
                    </section>
                    <div class="cardlist card large">
                        <label for="market_notes">Market Notes:</label>
                        <textarea name="market_notes" id="market_notes" class="" rows="8" placeholder="Enter any relevant notes or comments from the Market day.">@isset($market_day->market_notes){{ $market_day->market_notes }}@endisset</textarea>
                    </div>
                    <input type="hidden" name="state" value="3" />
                    <button class="button" type="submit">Mark as Returned <i class="fas fa-check"></i></button>

                    @break

                @case('Returned')
                    <ul class="card-list">
                        <li class="card"><label for="employee">Market Employee(s):</label> <input name="employee" id="employee" type="text" @isset($market_day->employee) value="{{ $market_day->employee }}" @endisset /></li>
                    </ul>  
                    <section class="products">
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
                                <td><input name="packed[{{ $item->id }}]" type="number" step="0.25" max="60" @isset($item->packed) value="{{ $item->packed + 0 }}" @endisset /></td>
                                <td><input name="returned[{{ $item->id }}]" type="number" step="0.25" max="{{ $item->packed }}" @isset($item->returned)value="{{ $item->returned + 0 }}"@endisset /></td>
                                <td>{{ $item->packed - $item->returned }}</td>
                                <td>${{ $item->products->price * ($item->packed - $item->returned) }}</td>
                            </tr>
                        @endforeach
                            </tbody>
                        </table>
                    </section>                       
                    <div>
                        <strong>Estimated Revenue:</strong> ${{ $market_day->estimated_revenue }}
                    </div>
                    <div>
                        <strong>Actual Revenue:</strong>
                        <input name="actual_revenue" type="number" @isset($market_day->actual_revenue) value="{{ $market_day->actual_revenue }}" @endisset />
                    </div>
                    <input type="hidden" name="state" value="4" />
                    <section>
                        <button class="button" type="submit">Complete this Market! <i class="fas fa-check"></i></button>
                    </section>

                    @break

                @case('Completed')
                    <ul class="card-list">
                        <li class="card"><label for="employee">Market Employee(s):</label> <input name="employee" id="employee" type="text" @isset($market_day->employee) value="{{ $market_day->employee }}" @endisset /></li>
                    </ul>  
                    <section class="products">
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
                                <td><input name="packed[{{ $item->id }}]" type="number" step="0.25" max="60" @isset($item->packed) value="{{ $item->packed }}" @endisset /></td>
                                <td><input name="returned[{{ $item->id }}]" type="number" step="0.25" max="{{ $item->packed }}" @isset($item->returned)value="{{ $item->returned }}"@endisset /></td>
                                <td>{{ $item->packed - $item->returned }}</td>
                                <td>${{ $item->products->price * ($item->packed - $item->returned) }}</td>
                            </tr>
                        @endforeach
                            </tbody>
                        </table>
                    </section>
                    <div>
                        <strong>Estimated Revenue:</strong> ${{ $market_day->estimated_revenue }}
                    </div>
                    <div>
                        <strong>Actual Revenue:</strong>
                        <input name="actual_revenue" type="number" @isset($market_day->actual_revenue) value="{{ $market_day->actual_revenue }}" @endisset />
                    </div>
                    <input type="hidden" name="state" value="4" />
                    <section>
                        <button class="button" type="submit">Save <i class="fas fa-check"></i></button>
                    </section>

                    @break
                    
            @endswitch
        </form>

        <footer>
            <a class="util notes_trigger @if($has_notes)has_notes @endif" href="#">
                <i class="far fa-comment-dots"></i>
            </a>
            <a class="util options_trigger" href="#">
                <i class="fas fa-cogs"></i>
            </a>
            <div class="notes">
                @if($market_day->admin_notes)
                    <div>
                        <strong>Admin Notes:</strong>
                        <p>{{ $market_day->admin_notes }}</p>
                    </div> 
                @endif                   
                @if($market_day->packing_notes)
                    <div>
                        <strong>Packing Notes:</strong>
                        <p>{{ $market_day->packing_notes }}</p>
                    </div>
                @endif    
                @if($market_day->market_notes)
                    <div>
                        <strong>Market Notes:</strong>
                        <p>{{ $market_day->market_notes }}</p>
                    </div>
                @endif 
            </div>
            <div class="market_day_options">
                <form method="POST" action="/market_days/{{ $market_day->id }}">
                    {{ csrf_field() }}
                    @method('PUT')

                    @switch($market_day->state)
                        @case('Ready To Pack')
                            <input type="hidden" name="state" value="0" />
                            <button type="submit">Revert to Draft</button>
                        @break
                        @case('Packed')
                            <input type="hidden" name="state" value="1" />
                            <button type="submit">Revert to "Ready to Pack"</button>
                        @break
                        @case('Returned')
                            <input type="hidden" name="state" value="2" />
                            <button type="submit">Revert to "Packed"</button>
                        @break
                        @case('Completed')
                            <input type="hidden" name="state" value="3" />
                            <button type="submit">Revert to "Returned"</button>
                        @break
                    @endswitch
                </form>

                <form method="POST" action="/market_days/{{ $market_day->id }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit">Delete</button>
                </form>
            </div>
        </footer>
           
    </div>
@endsection