<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OnReading. The on reading of a kanji
 * @package App\Models
 */
class OnReading extends Model
{
    protected $table = "kanji_on_reading";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo the kanji associated with this reading
     */
    public function kanji(){
        return $this->belongsTo(Kanji::class);
    }
}