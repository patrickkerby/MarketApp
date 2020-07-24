@extends('layout')

@section('content')
<nav>
  <a href="{{ $product->id }}/edit">Edit</a>
</nav>
    <div class="content">
        <h3>{{ $product->name }}</h3>
        <ul>
          <li>Category: {{ $categories->name }}</li>
          <li>Price: ${{ $product->price }}</li>
        </ul>
    </div>
@endsection