<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Integer;

class DictionaryEntry extends Model
{
    protected $table = "dictionary_entries";

    public function getJlpt(){
        $representation = $this->japanese_representations()->first()->representation;
        $kanji = Kanji::query()->where('literal', $representation)->first();

        if(!isset($kanji)) return null;

        return $kanji->jlpt_level;
    }

    public function meanings(){
        return $this->hasMany(DictionaryMeaning::class, 'entry_id');
    }

    public function japanese_representations(){
        return $this->hasMany(DictionaryJapaneseRepresentation::class, 'entry_id');
    }

    public function kana_representations(){
        return $this->hasMany(DictionaryKanaRepresentation::class, 'entry_id');
    }

    static public function sort(Collection $entries, $query){
        $entries->load('meanings', 'japanese_representations');
        $sortedEntries =
            $entries->sort(function($a, $b) use($query){
                $aFreq = isset($a->frequency) ? $a->frequency : 999999999;
                $bFreq = isset($b->frequency) ? $b->frequency : 999999999;

                $aScore = $a->relevence + (100000/$aFreq);
                $bScore = $b->relevence + (100000/$bFreq);

                if(isset($a->frequency) && !isset($b->frequency)){
                    $aScore += 10000;
                }

                if(isset($b->frequency) && !isset($a->frequency)){
                    $bScore += 10000;
                }

                //If the query is exactly the meaning give this result
                foreach ($a->meanings as $meaning){
                    if($meaning->meaning == $query){
                        $aScore += 10000;
                    }
                }
                foreach ($b->meanings as $meaning){
                    if($meaning->meaning == $query){
                        $bScore += 10000;
                    }
                }

                return $aScore < $bScore ? 1 : -1;
            });

        return $sortedEntries;
    }
}