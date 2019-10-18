<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictionaryEntryKanaJapaneseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionary_entry_kana_japanese', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('kana_id')->unsigned();
            $table->bigInteger('japanese_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('dictionary_entry_kana_japanese', function(Blueprint $table){
            $table->foreign('kana_id')
                ->references('id')
                ->on('dictionary_entry_kana')
                ->onDelete('cascade');

            $table->foreign('japanese_id')
                ->references('id')
                ->on('dictionary_entry_japanese')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dictionary_entry_kana_japanese', function(Blueprint $table){
            $table->dropForeign(['kana_id']);
            $table->dropForeign(['japanese_id']);
        });

        Schema::dropIfExists('dictionary_entry_kana_japanese');
    }
}
