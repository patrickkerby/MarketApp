@extends('layout')

@section('content')
    <div>
      <a href="/markets/create" class="button">Add new market</a>
    </div>
    <div class="content">
      <ul>
        @foreach ($markets as $market)
        <li><span>{{ $market->sort_order }}</span> - <a href="/markets/{{ $market->id }}/edit">{{ $market->name }}</a></li>
        @endforeach
      </ul>
    </div>
@endsection