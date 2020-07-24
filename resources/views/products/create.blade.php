@extends('layout')

@section('content')
    <div id="wrapper">
      <div id="page" class="container">
        <h2>New Product</h2>
        <form method="POST" action="/products">
          @csrf
          
          <div class="field">
            <label class="label" for="name">Name</label>

            <div class="control">
              <input class="input" type="text" name="name" id="name" value="{{ old('name') }}" />
              @error('name')
                <p class="error">{{ $errors->first('name') }}</p>
              @enderror
            </div>
          </div>

          <div class="field">
            <label class="label" for="price">Price</label>

            <div class="control">
              <input class="input" type="number" step="0.25" name="price" id="price" value="{{ old('price') }}"  />
            </div>
            @error('price')
              <p class="error">{{ $errors->first('price') }}</p>
            @enderror
          </div>

          <div class="field">
            <label class="label" for="category_id">Category</label>

            <div class="control">              
              <select name="category_id" id="category_id" value="">
                @foreach($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="field">
            <div class="control">
              <button class="button" type="submit">Create Product</button>
            </div>

          </div>
          
        </form>
      </div>
    </div>
@endsection