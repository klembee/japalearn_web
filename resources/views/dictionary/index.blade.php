<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<body>
    <h1>Index</h1>
    <form method="get" action="{{route('search')}}">
        <input type="text" name="query" />
        <button>Search</button>
    </form>
</body>
</html>