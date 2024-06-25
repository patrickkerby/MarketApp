@extends('layout')

@section('content')

  <div class="col-11 col-sm-8 col-lg-6">
    <header class="row justify-content-center">
      <h1>Products</h1>
      <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
    </header> 

      @foreach($categorized_products as $key => $item)

        <h2>{{$key}}:</h2>
        <ul class="card-list">
          @foreach($item as $item)
            @unless ($item->archived == true)
              <li class="card">
                <a href="/products/{{ $item->id }}/edit">
                  <label>{{ $item->name }}:</label>
                  <span class="setInput">${{ $item->price }}</span>
                  <span class="setInput"><i class="far fa-edit"></i></span>
                </a>
              </li> 
            @endunless         
          @endforeach
        </ul>
      @endforeach

  </div>
  <footer>
    <a class="util options_trigger" data-toggle="collapse" href="#product_options" role="button" aria-expanded="false" aria-controls="product_options">
      <i class="fas fa-cogs"></i>
    </a>
    <div class="collapse panel-body" id="product_options">
        <a class="secondary-action" href="#" data-toggle="modal" data-target="#archivedProducts">Archived Products</a>
    </div>
    <a href="/products/create" class="button main-action">Add new product</a>
  </footer>


<!-- Modal -->
<div class="modal fade" id="archivedProducts" tabindex="-1" role="dialog" aria-labelledby="archivedProducts" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">      
      <div class="modal-header">
        <h5 class="modal-title" id="archivedProductsLabel">Archived Products</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <ul class="card-list">
          @foreach($products as $item)
            @if ($item->archived == true)
              <li class="card">
                <a href="/products/{{ $item->id }}/edit">
                  <label>{{ $item->name }}:</label>
                  <span class="setInput"><i class="far fa-edit"></i></span>
                </a>
              </li> 
            @endif         
          @endforeach
        </ul>
      <div class="modal-footer">
        <button type="button" class="button btn btn-secondary secondary-action" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection