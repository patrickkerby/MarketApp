@extends('layout')

@section('content')
    <div id="wrapper">
      <div id="page" class="container">
        <h2>Edit Category</h2>
        <form method="POST" action="/categories/{{ $category->id }}">
          @csrf
          @method('PUT')
          
          <div class="field">
            <label class="label" for="name">Name</label>

            <div class="control">
              <input class="input" type="text" name="name" id="name" value="{{ $category->name }}" />
            </div>
          </div>

          <div class="field">
            <label class="label" for="desc">Description</label>

            <div class="control">
              <textarea name="desc" id="desc" cols="30" rows="10">{{ $category->desc }}</textarea>
            </div>
          </div>

          <div class="field">
            <div class="control">
              <button class="button" type="submit">Update Category</button>
            </div>

          </div>
          
        </form>
      </div>
    </div>
@endsection