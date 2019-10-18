<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class KunReading. Represents the kun reading of a kanji and contains the following fields:
 * - reading: The kun reading
 * @package App\Models
 */
class KunReading extends Model
{
    protected $table = "kanji_kun_reading";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo the kanji associciated with this reading
     */
    public function kanji(){
        return $this->belongsTo(Kanji::class);
    }
}