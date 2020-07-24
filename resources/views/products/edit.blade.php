@extends('layout')

@section('content')
    <div id="wrapper">
      <div id="page" class="container">
        <h2>Edit Product</h2>
        <form method="POST" action="/products/{{ $product->id }}">
          @csrf
          @method('PUT')
          
          <div class="field">
            <label class="label" for="name">Name</label>

            <div class="control">
              <input class="input" type="text" name="name" id="name" value="{{ $product->name }}" />
            </div>
          </div>

          <div class="field">
            <label class="label" for="price">Price</label>

            <div class="control">
              <input class="input" type="number" step="0.25" name="price" id="price" value="{{ $product->price }}" />
            </div>
          </div>

          <div class="field">
            <label class="label" for="category_id">Category</label>

            <div class="control">              
              <select name="category_id" id="category_id" value="{{ $product->category_id }}">
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" @if ($category->id === $product->category_id ) selected @endif>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            
          </div>

          <div class="field">
            <div class="control">
              <button class="button" type="submit">Update Product</button>
            </div>

          </div>
          
        </form>
      </div>
    </div>
@endsection