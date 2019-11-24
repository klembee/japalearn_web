<?php

namespace App\Models;


use App\Utils\UnicodeUtil;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Integer;

class DictionaryEntry extends Model
{
    protected $table = "dictionary_entries";
    protected $appends = ["kanjis", "hasJapaneseRepresentation", "hasKanaRepresentation"];

    public function getJlpt(){
        if($this->isKanji()){
            $kanji = Kanji::query()->where('literal', $this->getRepresentation())->first();

            if(!isset($kanji)) return null;

            return $kanji->jlpt_level;
        }else{
            return null;
        }
    }

    public function getGrade(){
        if($this->isKanji()){
            $kanji = Kanji::query()->where('literal', $this->getRepresentation())->first();

            if(!isset($kanji)) return null;

            return $kanji->grade;
        }else{
            return null;
        }
    }

    private function isKanji(){
        $representation = $this->getRepresentation();
        if(mb_strlen($representation) > 1) return false;
        if(\IntlChar::ord($representation) < UnicodeUtil::$KANJI_UNICODE_START) return false;
        return true;
    }

    private function getRepresentation(){
        $representation = null;
        try {
            $representation = $this->japanese_representations()->firstOrFail()->representation;
        }catch(ModelNotFoundException $e){
            return null;
        }

        return $representation;
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

    public function getHasJapaneseRepresentationAttribute(){
        return $this->japanese_representations->count() > 0;
    }

    public function getHasKanaRepresentationAttribute(){
        return $this->kana_representations->count() > 0;
    }

    public function getKanjisAttribute(){
        $kanjis = [];
        $representation = $this->getRepresentation();
        if($representation == null) return [];

        foreach(mb_str_split($representation) as $character){
            //If the character is a kanji
            if(\IntlChar::ord($character) >= UnicodeUtil::$KANJI_UNICODE_START && \IntlChar::ord($character) <= UnicodeUtil::$KANJI_UNICODE_END){
                $kanjis[] = Kanji::query()->where('literal', $character)->first();
            }
        }

        return $kanjis;
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

                if(isset($b->frequency) &&
                    !isset($a->frequency)){
                    $bScore += 10000;
                }

                //If the query is exactly the meaning give this result
                $meaningsIndexStart = $a->meanings[0]->id;
                foreach ($a->meanings as $meaning){
                    if($query == $meaning->meaning){
                        $aScore += 40000;
                    }
                    if(in_array($query, explode(';', $meaning->meaning))){
                        $aScore += 10000;
                    }
                    if(mb_strpos($meaning, $query) !== false){
                        $aScore += 10000;
                    }
                }
                foreach ($b->meanings as $meaning){
                    if($query == $meaning->meaning){
                        $bScore += 40000;
                    }
                    if(in_array($query, explode(';', $meaning->meaning))){
                        $bScore += 10000;
                    }
                    if(mb_strpos($meaning, $query) !== false){
                        $bScore += 10000;
                    }
                }

                return $aScore < $bScore ? 1 : -1;
            });

        return $sortedEntries->values();
    }
}