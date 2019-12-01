<?php
/**
 * Created by PhpStorm.
 * User: cleme
 * Date: 2019-11-30
 * Time: 4:04 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Lexic extends Model
{
    protected $table = "lexic";
    protected $fillable = ["user_id", "word", "level"];

    public function meanings(){
        return $this->hasMany(LexicMeaning::class);
    }
}