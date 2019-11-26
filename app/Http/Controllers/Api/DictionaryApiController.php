<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\DictionaryEntry;
use App\Models\DictionaryJapaneseRepresentation;
use App\Utils\HepburnUtils;
use App\Utils\UnicodeUtil;
use Doctrine\DBAL\Query\QueryBuilder;
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

        $entries = DictionaryEntry::query()->select(['id', 'relevence', 'frequency'])->whereHas('kana_representations', function(Builder $q) use ($hiraganaQuery){
            return $q->where('representation', '=', "$hiraganaQuery");
        })->orWhereHas('meanings', function($q) use($query){
            return $q->where('meaning', 'LIKE', "%$query%");
        })->with([
            'kana_representations' => function($query){
                $query->select(['id', 'representation']);
            },
        ]);

        return $entries->with('kana_representations')->get();
    }
}