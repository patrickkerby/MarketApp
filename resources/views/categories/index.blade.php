@extends('layout')

@section('content')
    <div>
      <a href="/categories/create" class="button">Add new Category</a>
    </div>
    <div class="content">
      <ul>
        @foreach ($categories as $category)
        <li><a href="/categories/{{ $category->id }}/edit">{{ $category->name }}</a></li>
        @endforeach
      </ul>
    </div>
@endsection