<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexForRepresentations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dictionary_entry_japanese', function(Blueprint $table){
            $table->index(['representation', 'entry_id']);
        });
        Schema::table('dictionary_entry_kana', function(Blueprint $table){
            $table->index(['representation', 'entry_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dictionary_entry_japanese', function(Blueprint $table){
            $table->dropIndex(['representation']);
        });
        Schema::table('dictionary_entry_kana', function(Blueprint $table){
            $table->dropIndex(['representation']);
        });
    }
}
