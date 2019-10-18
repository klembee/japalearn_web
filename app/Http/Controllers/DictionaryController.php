<?php
namespace App\Http\Controllers;


use App\Models\DictionaryEntry;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    public function index(Request $request){
        return "index";//view('dictionary.index');
    }

    public function view(Request $request, $id){
        $entry = DictionaryEntry::query()->where('id', $id)->first();
        $entry->load(['meanings', 'japanese_representations', 'kana_representations', 'japanese_representations.kana_representations']);

        return view('dictionary.view', compact('entry'));
    }

    public function search(Request $request){
        $this->validate($request, [
            'query' => 'required|string'
        ]);

        $query = $request->get('query');
        $entries = DictionaryEntry::query()->whereHas('meanings', function($query) use ($request){
            return $query->where('meaning', 'LIKE', "%{$request->get('query')}%");
        })->get();

        $entries->load(['japanese_representations', 'kana_representations']);

        return view('dictionary.search', compact('entries', 'query'));
    }
}