<?php
/**
 * Created by PhpStorm.
 * User: cleme
 * Date: 2019-10-17
 * Time: 9:11 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DictionaryJapaneseRepresentation extends Model
{
    protected $table = "dictionary_entry_japanese";

    public function kana_representations(){
        return $this->belongsToMany(DictionaryKanaRepresentation::class, 'dictionary_entry_kana_japanese', 'japanese_id', 'kana_id');
    }
}