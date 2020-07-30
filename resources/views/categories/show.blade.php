@extends('layout')

@section('content')
    <div class="content">
        <div class="title m-b-md">
            <h2>{{ $category->name }}</h2>
        </div>
        <p>{{ $category->description }}</p>                
    </div>
@endsection