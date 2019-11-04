<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Kanji. Represents a kanji and has the following fields:
 * - literal: The kanji symbol
 * - grade: The grade at which this kanji is taught at in Japan
 * - stoke_count: The number of strokes it contains
 * - jlpt_level: The jlpt level associated with this kanji
 */
class Kanji extends Model
{
    protected $table = "kanjis";
    protected $appends = [
        'meanings',
        'on_readings',
        'kun_readings'
    ];

    /**
     * Get the kun readings associated with this kanji
     * @return \Illuminate\Database\Eloquent\Relations\HasMany the kun readings
     */
    public function kunReadings(){
        return $this->hasMany(KunReading::class);
    }

    /**
     * Get the on readings associated with this kanji
     * @return \Illuminate\Database\Eloquent\Relations\HasMany the on readings
     */
    public function onReadings(){
        return $this->hasMany(OnReading::class);
    }

    /**
     * Get the meanings of this kanji
     * @return \Illuminate\Database\Eloquent\Relations\HasMany the meanings of this kanji
     */
    public function meanings(){
        return $this->hasMany(KanjiMeaning::class);
    }

    public function getMeaningsAttribute(){
        $meanings = [];
        foreach($this->meanings()->get() as $meaning){
            $meanings[] = $meaning->meaning;
        }

        return $meanings;
    }

    public function getOnReadingsAttribute(){
        $readings = [];
        foreach($this->onReadings()->get() as $reading){
            $readings[] = $reading->reading;
        }

        return $readings;
    }

    public function getKunReadingsAttribute(){
        $readings = [];
        foreach($this->kunReadings()->get() as $reading){
            $readings[] = $reading->reading;
        }

        return $readings;
    }
}
