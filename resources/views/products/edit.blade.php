@extends('layout')

@section('class', 'edit')

@section('content')

  <div class="col-11 col-sm-8 col-lg-6">
    <header class="row justify-content-center">
      @if($product->archived)
        <h1> Archived Product</h1>
      @else
        <h1>Edit Product</h1>
      @endif
      <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
    </header> 

    <form method="POST" id="editProduct" class="edit" action="/products/{{ $product->id }}/edit">
      @csrf
      @method('PUT')
      
      <ul class="card-list">
        <li class="card">
          <label class="label" for="name">Name</label>
          <input class="input" type="text" name="name" id="name" value="{{ $product->name }}" @if($product->archived) disabled @endif />
        </li>

        <li class="card">
          <label class="label" for="price">Price</label>
          <input class="input" type="number" step="0.25" name="price" id="price" value="{{ $product->price }}" @if($product->archived) disabled @endif />
        </li>

        <li class="card">
          <label class="label" for="category_id">Category</label>
            <select name="category_id" id="category_id" value="{{ $product->category_id }}">
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @if ($category->id == $product->category_id ) selected @endif @if($product->archived) disabled @endif>{{ $category->name }}</option>
              @endforeach
            </select>
        </li>
      </ul>
      <hr>
      @if($product->archived)
        <p class="product_meta">This is an archived Product</p>
      @else
        <p class="product_meta">Product has been used in {{ count($quantities) }} Market Days</p>
      @endif

      @if($errors->any())
        <div class="is-danger alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
              <li><p>{{ $error }}</p></li>
            @endforeach
          </ul>
        </div>
      @endif

      @if($product->archived)
        <input type="hidden" id="archived" name="archived" value="0">
      @endif

      <button class="button main-action" type="submit">
        @if($product->archived)
          Restore Product
        @else
          Update Product
        @endif
      </button>              
    </form>

    @if (session()->has('success'))
    
      <div id="test"> {{ session()->get('message') }}</div>

      <div class="col-sm-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session()->get('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    @endif

  </div>
  <footer>
    <a class="util options_trigger" data-toggle="collapse" href="#product_options" role="button" aria-expanded="false" aria-controls="product_options">
      <i class="fas fa-cogs"></i>
    </a>
    <div class="collapse" id="product_options">
      @if($deleteable)
      <form method="POST" action="/products/{{ $product->id }}"" onsubmit="return confirm('Are you sure you want to delete this product?')"> 
        {{  csrf_field() }}
        {{ method_field('DELETE') }}
          <button type="submit" class="btn">Delete Product</button>
      </form>
      @else
        <a href="" class="btn btn-outline-danger" data-toggle="modal" data-target="#exampleModal">
          Delete Product?
        </a>
      @endif
      @unless($product->archived)      
        <form method="POST" id="archived" action="/products/{{ $product->id }}/edit">
          @csrf
          @method('PUT')
          <input type="hidden" id="archived" name="archived" value="1">
          <button type="submit" class="btn">Archive Product</button>
        </form>
      @endunless
    </div>
  </footer>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" id="reassignIds" action="/products/{{ $product->id }}/edit">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Woah hold up!</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Before deleting, we need to assign a new product to all of the market days in the past that have used this product. Be careful! this will change the data for past market days, and is permanent!</p>
          
          <label class="label" for="newproduct_id">New Product:</label>
          <select name="newproduct_id" id="newproduct_id" value="{{ $product->id }}">
            @foreach ($products as $item)
              @unless ($item->archived == true)            
                <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endunless
            @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-sm btn-outline-success">Reassign data</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection