<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\DictionaryEntry;
use App\Models\DictionaryJapaneseRepresentation;
use App\Utils\HepburnUtils;
use App\Utils\UnicodeUtil;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DictionaryApiController extends Controller
{

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

        $entries->load(['japanese_representations', 'kana_representations']);
        $entries = DictionaryEntry::sort($entries, $query);
        $entries = $entries->take(20);

        return $entries;
    }
}