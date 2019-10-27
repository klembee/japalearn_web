<?php
namespace App\Http\Controllers;


use App\Models\DictionaryEntry;
use App\Models\DictionaryJapaneseRepresentation;
use App\Utils\HepburnUtils;
use App\Utils\UnicodeUtil;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DictionaryController extends Controller
{
    public function index(Request $request){
        return view('dictionary.index');
    }

    public function view(Request $request, $word){
        $entry = DictionaryEntry::query()->whereHas('japanese_representations', function(Builder $query) use($word){
            $query->where('representation', $word);
        })->first();

        $entry->load(['meanings', 'japanese_representations', 'kana_representations', 'japanese_representations.kana_representations']);

        return view('dictionary.view', compact('entry'));
    }

    public function search(Request $request){
        $this->validate($request, [
            'query' => 'required|string'
        ]);

        $query = $request->get('query');

        //Check if the query is in romanji and provide suggestion for search
        $romanjiQuery = HepburnUtils::toHiragana($query);
        $suggestion = "";

        //The query contains only romanji characters
        if($romanjiQuery[0]){
            $suggestion = $romanjiQuery[1];
        }

        $a = microtime(true);

        if(!UnicodeUtil::isOnlyJapaneseChars($query)) {
            $entries = DictionaryEntry::query()->whereHas('meanings', function ($q) use ($query) {
                return $q->where('meaning', 'LIKE', "$query%");
            })->get();
        }

        if((count($entries) == 0 && $romanjiQuery[0]) || UnicodeUtil::isOnlyJapaneseChars($query)){
            if(!UnicodeUtil::isOnlyJapaneseChars($query)){
                $query = $romanjiQuery[1];
            }

            //Search in the kanas
            $entries = DictionaryEntry::query()->whereHas('kana_representations', function($q) use ($query){
                return $q->where('representation', 'LIKE', "$query%");
            })->get();
        }

        $b = microtime(true);

        $entries->load(['japanese_representations', 'kana_representations']);
        $entries = DictionaryEntry::sort($entries, $query);
        $entries = $entries->take(20);

        $c = microtime(true);

        $queryTime = ($b - $a);
        $sortTime = ($c - $b);

        return view('dictionary.search', compact('entries', 'query', 'suggestion', 'queryTime', 'sortTime'));
    }
}