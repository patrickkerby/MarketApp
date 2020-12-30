@extends('layout')

@section('content')
    <div class="col-11 col-sm-8 col-lg-6">  
        <header class="row justify-content-center">
            <h1>
                <span class="d-print-none">{{ $market_day->state }}</span>
                {{ $market_day->market->name }}                
            </h1>
            <div class="date d-print-none">
                <span class="month">
                    {{ \Carbon\Carbon::parse($market_day->date)->format('M')}}.
                </span>
                <span class="day">
                    {{ \Carbon\Carbon::parse($market_day->date)->format('j')}}
                </span>
                <span class="year">
                    {{ \Carbon\Carbon::parse($market_day->date)->format('Y')}}
                </span>
                
            </div>
        </header> 
        <h3 class="d-none d-print-flex">{{ \Carbon\Carbon::parse($market_day->date)->format('M j, Y')}}</h3>
        <form method="POST" class="market_day_edit" action="/market_days/{{ $market_day->id }}">
            @csrf
            @method('PUT')                                    

            @switch($market_day->state)
                @case('Draft')
                    <ul class="card-list employee">
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
                                <input name="packed[{{ $item->id }}]" id="item{{ $item->id }}" type="number" min="0" step="0.25" max="60" @isset($item->packed) value="{{ $item->packed + 0 }}" @endisset />
                            </li>
                        @endforeach
                        </ul>
                    </section>
                    <div class="cardlist card large">
                        <label for="admin_notes">Admin Notes:</label>
                        <textarea name="admin_notes" id="admin_notes" class="" rows="8">@isset($market_day->admin_notes){{ $market_day->admin_notes }}@endisset</textarea>
                    </div>
                    <input type="hidden" name="state" value="1" />
                    <button class="button d-print-none" type="submit">Ready To Pack? <i class="fas fa-check"></i></button>
                    @break

                @case('Ready To Pack')
                    <div class="row justify-content-center introduction">
                        <p class="col-9">This market is ready to be packed. If any product are unavailable, or any changes to the numbers below need to be made, adjust them before you continue.</p>
                    </div>
                    <ul class="card-list employee">
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
                                <input name="packed[{{ $item->id }}]" id="item{{ $item->id }}" type="number" min="0" step="0.25" max="60" @isset($item->packed) value="{{ $item->packed + 0 }}" @endisset />
                            </li>
                        @endforeach
                        </ul>                        
                    </section>
                    <div class="cardlist card large">
                        <label for="packing_notes">Packing Notes:</label>
                        <textarea name="packing_notes" id="packing_notes" class="" rows="8" placeholder="Enter anything relevant from the process of packing the trucks.">@isset($market_day->packing_notes){{ $market_day->packing_notes }}@endisset</textarea>
                    </div>
                    <input type="hidden" name="state" value="2" />
                    <button class="button d-print-none" type="submit">Truck is packed <i class="fas fa-check"></i></button>
                    @break

                @case('Packed')
                    <div class="row justify-content-center introduction">
                        <p class="col-9">Now that market is over, count how many of each product is leftover, and report it below!</p>
                    </div>
                    <ul class="card-list employee">
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
                            <span class="setInput">{{ $item->packed + 0 }}</span>
                            <input name="returned[{{ $item->id }}]" id="item{{ $item->id }}" type="number" min="0" step="0.25" max="{{ $item->packed }}" @isset($item->returned)value="{{ $item->returned + 0 }}"@endisset />
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
                    <button class="button d-print-none" type="submit">Mark as Returned <i class="fas fa-check"></i></button>

                    @break

                @case('Returned')
                    <ul>
                        @if ($market_day->employee)
                        <li>
                            <strong>Employee:</strong>
                            {{ $market_day->employee ?? '' }}
                        </li>
                        @endif
                        <li>
                            <strong>Estimated Revenue:</strong>
                            ${{ $market_day->estimated_revenue }}
                        </li>
                    </ul>
                    <ul class="card-list">
                        <li class="card small revenue">
                            <label for="actual_revnue">Actual Revenue:</label>
                            <span class="prefix">$</span>
                            <input name="actual_revenue" id="actual_revenue" type="number" @isset($market_day->actual_revenue) value="{{ $market_day->actual_revenue }}" @endisset />
                        </li>                        
                    </ul>  
                    <section class="products">
                        <div class="list-head">
                            <h4 class="product">Product</h4>
                            <h4 class="packed">Packed</h4>
                            <h4 class="returned">Unsold</h4>
                            <h4 class="revenue">~$</h4>
                        </div>
                        <ul class="card-list">
                        @foreach($product_quantities as $item)
                            <li class="card">
                                <label>{{ $item->products->name }}</label>
                                <input name="packed[{{ $item->id }}]" type="number" min="0" step="0.25" max="60" @isset($item->packed) value="{{ $item->packed + 0 }}" @endisset />
                                <input name="returned[{{ $item->id }}]" type="number" min="0" step="0.25" max="{{ $item->packed }}" @isset($item->returned)value="{{ $item->returned + 0 }}"@endisset />
                                <span class="setInput">${{ $item->products->price * ($item->packed - $item->returned) }}</span>
                            </li>
                        @endforeach
                        </ul> 
                    </section>                       
                    <input type="hidden" name="state" value="4" />
                    <section>
                        <button class="button d-print-none" type="submit">Complete this Market! <i class="fas fa-check"></i></button>
                    </section>

                    @break

                @case('Completed')
                    <ul>
                        @if ($market_day->employee)
                        <li>
                            <strong>Employee:</strong>
                            {{ $market_day->employee ?? '' }}
                        </li>
                        @endif
                        <li>
                            <strong>Estimated Revenue:</strong>
                            ${{ $market_day->estimated_revenue }}
                        </li>                        
                    </ul>
                    <ul class="card-list">
                        <li class="card small revenue">
                            <label for="actual_revnue">Actual Revenue:</label>
                            <span class="prefix">$</span>
                            <input name="actual_revenue" id="actual_revenue" type="number" @isset($market_day->actual_revenue) value="{{ $market_day->actual_revenue }}" @endisset />
                        </li>                        
                    </ul> 
                    <section class="products">
                        <div class="list-head">
                            <h4 class="product">Product</h4>
                            <h4 class="packed">Packed</h4>
                            <h4 class="returned">Unsold</h4>
                            <h4 class="revenue">~$</h4>
                        </div>
                        <ul class="card-list">
                        @foreach($product_quantities as $item)
                            <li class="card">
                                <label>{{ $item->products->name }}</label>
                                <input name="packed[{{ $item->id }}]" type="number" min="0" step="0.25" max="60" @isset($item->packed) value="{{ $item->packed + 0 }}" @endisset />
                                <input name="returned[{{ $item->id }}]" type="number" step="0.25" max="{{ $item->packed }}" @isset($item->returned)value="{{ $item->returned + 0 }}"@endisset />
                                <span class="setInput">${{ $item->products->price * ($item->packed - $item->returned) }}</span>
                            </li>
                        @endforeach
                        </ul> 
                    </section>                     
                    <input type="hidden" name="state" value="4" />
                    <section>
                        <button class="button d-print-none" type="submit">Save <i class="fas fa-check"></i></button>
                    </section>

                    @break
                    
            @endswitch
        </form>

        <footer class="d-print-none">
            <a class="util notes_trigger @if($has_notes)has_notes @endif" data-toggle="collapse" href="#notes" role="button" aria-expanded="false" aria-controls="notes">
                <i class="far fa-comment-dots"></i>
            </a>
            <a class="util options_trigger" data-toggle="collapse" href="#market_day_options" role="button" aria-expanded="false" aria-controls="market_day_options">
                <i class="fas fa-cogs"></i>
            </a>
            
            <div class="collapse" id="market_day_options">
                <button class="print-window revert">
                    <i class="fas fa-print"></i> Print
                </button>
                <form method="POST" action="/market_days/{{ $market_day->id }}">
                    {{ csrf_field() }}
                    @method('PUT')

                    @switch($market_day->state)
                        @case('Ready To Pack')
                            <input type="hidden" name="state" value="0" />
                            <button class="revert" type="submit"><i class="fas fa-undo"></i> Revert to Draft</button>
                        @break
                        @case('Packed')
                            <input type="hidden" name="state" value="1" />
                            <button class="revert" type="submit"><i class="fas fa-undo"></i> Revert to "Ready to Pack"</button>
                        @break
                        @case('Returned')
                            <input type="hidden" name="state" value="2" />
                            <button class="revert" type="submit"><i class="fas fa-undo"></i> Revert to "Packed"</button>
                        @break
                        @case('Completed')
                            <input type="hidden" name="state" value="3" />
                            <button class="revert" type="submit"><i class="fas fa-undo"></i> Revert to "Returned"</button>
                        @break
                    @endswitch
                </form>

                <form method="POST" action="/market_days/{{ $market_day->id }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit"><i class="far fa-trash-alt"></i> Delete</button>
                </form>
            </div>
        </footer>
        <div class="notes collapse row no-gutters justify-content-center d-print-flex" id="notes">
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
           
    </div>
@endsection