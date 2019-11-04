<?php
/**
 * Created by PhpStorm.
 * User: cleme
 * Date: 2019-10-16
 * Time: 11:05 PM
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\DictionaryEntry;
use App\Models\Kanji;
use Illuminate\Http\Request;

class KanjiApiController extends Controller
{
    /**
     * Lists all the kanjis
     */
    public function list(Request $request){
        $kanjis =  Kanji::all();
        return $kanjis;
    }

    /**
     * Get the categories and
     */
    public function categories(Request $request){
        $kanjis =  Kanji::query()->where('category', '!=', '')->get(['id', 'literal', 'grade', 'stroke_count', 'frequency', 'jlpt_level', 'category'])->groupBy('category');

        $a = array();
        foreach ($kanjis->keys() as $key){
            $entry = [];
            $entry['title'] = $key;
            $entry['kanjis'] = $kanjis->get($key);
            $a[] = $entry;
        }


        return response()->json([
            'success' => true,
            'message' => "",
            'categories' => $a
        ]);
    }

    /**
     * Get the kanjis associated with a jlpt level
     */
    public function jlptLevel(Request $request, $level){
        $kanjis = Kanji::query()->where('jlpt_level', $level)->get();
        return $kanjis->load('onReadings', 'kunReadings','meanings');
    }

    public function view(Request $request, $kanji){
        $kanjiElement = Kanji::query()->where('literal', $kanji)->first();
        return $kanjiElement;
    }
}