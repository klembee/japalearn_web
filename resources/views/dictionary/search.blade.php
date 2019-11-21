@extends('defaultLayout')
@section("content")
    <h1>Search for <b>{{$query}}</b></h1>
    @if(mb_strlen($suggestion) > 0)
        <p>Did you mean: <a href="{{route('search', ['query' => $suggestion])}}">{{$suggestion}}</a></p>
    @endif

    <p>Query time: {{$queryTime}}</p>
    <p>Sort time: {{$sortTime}}</p>

    @foreach($entries as $entry)
        <div>
            @if($entry->hasJapaneseRepresentation)
                <h1><ruby>{{$entry->japanese_representations[0]->representation}} @if($entry->hasKanaRepresentation)<rp>(</rp><rt>{{$entry->kana_representations[0]->representation}}</rt><rp>)</rp>@endif</ruby></h1>
            @elseif($entry->hasKanaRepresentation)<h1>{{$entry->kana_representations[0]->representation}}</h1>@endif

            <h3>Meanings</h3>
            <ol>
                @foreach($entry->meanings as $meaning)
                    <li>{{$meaning->meaning}}</li>
                @endforeach
            </ol>
        </div>
    @endforeach
@endsection