<?php

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Model representing a feedback in the database
 */
class Feedback extends Model
{
    protected $table = "feedbacks";
    protected $fillable = ['user_id', 'is_bug', 'section', 'description'];

    /**
     * Get the user that created this feedback
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}