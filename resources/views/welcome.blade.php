@extends('layout')

@section('content')
    <div class="content">
        <div class="title m-b-md">
            Market App
        </div>
        <ul>
            @foreach ($markets as $market)

                <li>{{ $market->name }}</li>

            @endforeach
        </ul>                
    </div>
@endsection