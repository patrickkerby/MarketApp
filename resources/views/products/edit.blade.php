@extends('layout')

@section('class', 'edit')

@section('content')

  <div class="col-11 col-sm-8 col-lg-6">
    <header class="row justify-content-center">
      <h1>Edit Product</h1>
      <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
    </header> 

    <form method="POST" class="edit" action="/products/{{ $product->id }}">
      @csrf
      @method('PUT')
      
      <ul class="card-list">
        <li class="card">
          <label class="label" for="name">Name</label>
          <input class="input" type="text" name="name" id="name" value="{{ $product->name }}" />
        </li>

        <li class="card">
          <label class="label" for="price">Price</label>
          <input class="input" type="number" step="0.25" name="price" id="price" value="{{ $product->price }}" />
        </li>

        <li class="card">
          <label class="label" for="category_id">Category</label>
            <select name="category_id" id="category_id" value="{{ $product->category_id }}">
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @if ($category->id === $product->category_id ) selected @endif>{{ $category->name }}</option>
              @endforeach
            </select>
        </li>
      </ul>
        
      <button class="button" type="submit">Update Product</button>              

    </form>

    

  </div>
  <footer>
    <a class="util options_trigger" data-toggle="collapse" href="#product_options" role="button" aria-expanded="false" aria-controls="product_options">
      <i class="fas fa-cogs"></i>
    </a>
    <div class="collapse" id="product_options">

      <form method="POST" action="/products/product/{{ $product->id }}">
        {{  csrf_field() }}
        {{ method_field('DELETE') }}
  
        <button type="submit"><i class="far fa-trash-alt"></i> Delete</button>
      </form>
    </div>
  </footer>

@endsection