@extends('layout')

@section('content')
    <div class="content">        
        
        <h1>{{ $market_day->market->name }} Market ({{ $market_day->state }})</h1>
        <h3>{{ \Carbon\Carbon::parse($market_day->date)->format('F j, Y')}}</h3>
        <form method="POST" action="/market_days/{{ $market_day->id }}">
            @csrf
            @method('PUT')            
            <ul>
                <li><strong>Date:</strong> <input type="date" name="date" @isset($market_day->date) value="{{ $market_day->date }}" @endisset /></li>
                <li><strong>Market Employee(s):</strong> <input name="employee" type="text" @isset($market_day->employee) value="{{ $market_day->employee }}" @endisset /></li>
            </ul>              

            @switch($market_day->state)
                @case('Draft')
                    <section class="products">
                        <table>
                            <thead>
                                <tr>
                                    <td><h4>Product</h4></td>
                                    <td><h4>Packed</h4></td>
                                </tr>
                            </thead>
                            <tbody>
                        @foreach($product_quantities as $item)
                            <tr>
                                <td>{{ $item->products->name }}</td>
                                <td><input name="packed[{{ $item->id }}]" type="number" step="0.25" max="60" @isset($item->packed) value="{{ $item->packed }}" @endisset /></td>
                            </tr>
                        @endforeach
                            </tbody>
                        </table>
                    </section>

                    <h4>Admin Notes:</h4>
                    <textarea name="admin_notes" class="" rows="8">@isset($market_day->admin_notes){{ $market_day->admin_notes }}@endisset</textarea>
                    <input type="hidden" name="state" value="1" />
                    <button class="button" type="submit">Ready To Pack?</button>
                    @break

                @case('Ready To Pack')
                    <section class="products">
                        <table>
                            <thead>
                                <tr>
                                    <td><h4>Product</h4></td>
                                    <td><h4>Packed</h4></td>
                                </tr>
                            </thead>
                            <tbody>
                        @foreach($product_quantities as $item)
                            <tr>
                                <td>{{ $item->products->name }}</td>
                                <td><input name="packed[{{ $item->id }}]" type="number" step="0.25" max="60" @isset($item->packed) value="{{ $item->packed }}" @endisset /></td>
                            </tr>
                        @endforeach
                            </tbody>
                        </table>
                    </section>

                    @if($market_day->admin_notes)
                        <div>
                            <strong>Admin Notes:</strong>
                            <p>{{ $market_day->admin_notes }}</p>
                        </div> 
                    @endif 
                    <div>
                        <strong>Packing Notes:</strong>
                        <textarea name="packing_notes" class="" rows="8">@isset($market_day->packing_notes){{ $market_day->packing_notes }}@endisset</textarea>
                    </div>
                    <input type="hidden" name="state" value="2" />
                    <button class="button" type="submit">This Market is packed and ready to go!</button>
                    @break

                @case('Packed')
                    
                    <p>Now that market is over, count how many of each product is leftover, and report it below!</p>
                    <section class="products">
                        <table>
                            <thead>
                                <tr>
                                    <td><h4>Product</h4></td>
                                    <td><h4>Packed</h4></td>
                                    <td><h4>Returned</h4></td>
                                </tr>
                            </thead>
                            <tbody>
                        @foreach($product_quantities as $item)
                            <tr>
                                <td>{{ $item->products->name }}</td>
                                <td>{{ $item->packed }}</td>
                                <td><input name="returned[{{ $item->id }}]" type="number" step="0.25" max="{{ $item->packed }}" @isset($item->returned)value="{{ $item->returned }}"@endisset /></td>
                            </tr>
                        @endforeach
                            </tbody>
                        </table>
                    </section>

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
                    <div>
                        <strong>Market Notes:</strong>
                        <textarea name="market_notes" class="" rows="8">@isset($market_day->market_notes){{ $market_day->market_notes }}@endisset</textarea>
                    </div>
                    <input type="hidden" name="state" value="3" />
                    <button class="button" type="submit">Mark as Returned</button>

                    @break

                @case('Returned')
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
                    <div>
                        <strong>Estimated Revenue:</strong> ${{ $market_day->estimated_revenue }}
                    </div>
                    <div>
                        <strong>Actual Revenue:</strong>
                        <input name="actual_revenue" type="number" @isset($market_day->actual_revenue) value="{{ $market_day->actual_revenue }}" @endisset />
                    </div>
                    <input type="hidden" name="state" value="4" />
                    <section>
                        <button class="button" type="submit">Complete this Market!</button>
                    </section>

                    @break

                @case('Completed')
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
                    <div>
                        <strong>Estimated Revenue:</strong> ${{ $market_day->estimated_revenue }}
                    </div>
                    <div>
                        <strong>Actual Revenue:</strong>
                        <input name="actual_revenue" type="number" @isset($market_day->actual_revenue) value="{{ $market_day->actual_revenue }}" @endisset />
                    </div>
                    <input type="hidden" name="state" value="4" />
                    <section>
                        <button class="button" type="submit">Save</button>
                    </section>

                    @break
                    
            @endswitch
        </form>
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
@endsection