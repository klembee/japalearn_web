<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
    </head>
    <body>
        <h1><span>@if(isset($entry->japanese_representations[0])){{$entry->japanese_representations[0]->representation}} @endif</span>  (@if(isset($entry->kana_representations[0])){{$entry->kana_representations[0]->representation}})@endif</h1>

        <div>
            <h3>Other representations</h3>
            <h2>Meanings</h2>
            <ol>
                @foreach($entry->meanings as $meaning)
                    <p>{{$meaning->types}}</p>
                    <li><b>{{$meaning->meaning}}</b> <i>{{$meaning->misc}} {{$meaning->information}}</i></li>
                @endforeach
            </ol>
            <h2>Other forms</h2>
            <ol>
                @foreach($entry->japanese_representations as $representation)
                    <li>
                        <b>{{$representation->representation}}</b> @if(isset($representation->kana_representations[0]))({{ $representation->kana_representations[0]->representation}})@else <span>({{$entry->kana_representations[0]->representation}})</span>@endif
                    </li>
                @endforeach
            </ol>

        </div>
    </body>
</html>
