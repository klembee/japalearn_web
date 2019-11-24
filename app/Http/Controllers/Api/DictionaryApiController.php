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

        //Search in the kanas
        $entries = self::searchInDictionary($query);

        $entries->load(['japanese_representations', 'kana_representations']);
        $entries = DictionaryEntry::sort($entries, $query);
        $entries = $entries->take(20);

        return response()->json([
            'success' => true,
            'message' => "",
            'entries' => $entries
        ]);
    }

    static public function searchInDictionary($query){
        $hepburnResponse = HepburnUtils::toHiragana($query);

        error_log("asdf " . $hepburnResponse[1]);

        if($hepburnResponse[0]){
            $hiraganaQuery = $hepburnResponse[1];
        }else{
            $hiraganaQuery = $query;
        }

        $entries = DictionaryEntry::query()->whereHas('japanese_representations', function($q) use ($hiraganaQuery){
            return $q->where('representation', '=', "$hiraganaQuery");
        })->orWhereHas('kana_representations', function($q) use ($hiraganaQuery){
            return $q->where('representation', '=', "$hiraganaQuery");
        });

        //If the query is in english
        if(!$hepburnResponse[0] || $entries->count() == 0){
            $entries->orWhereHas('meanings', function($q) use($query){
                return $q->where('meaning', 'LIKE', "%$query%");
            });
        }

        return $entries->get();
    }
}