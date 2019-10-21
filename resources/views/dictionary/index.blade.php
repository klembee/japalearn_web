@extends('defaultLayout')
@section('content')
    <h1>Index</h1>
    <form method="get" action="{{route('search')}}">
        <input type="text" name="query" />
        <button>Search</button>
    </form>
@endsection