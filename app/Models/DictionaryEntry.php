<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DictionaryEntry extends Model
{
    protected $table = "dictionary_entries";

    public function meanings(){
        return $this->hasMany(DictionaryMeaning::class, 'entry_id');
    }

    public function japanese_representations(){
        return $this->hasMany(DictionaryJapaneseRepresentation::class, 'entry_id');
    }

    public function kana_representations(){
        return $this->hasMany(DictionaryKanaRepresentation::class, 'entry_id');
    }
}