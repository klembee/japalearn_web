<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KanjiMeaning. Represents the meaning of a kanji
 * @package App\Models
 */
class KanjiMeaning extends Model
{
    protected $table = "kanji_meaning";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo the kanji associated with this meaning
     */
    public function kanji(){
        return $this->belongsTo(Kanji::class);
    }
}