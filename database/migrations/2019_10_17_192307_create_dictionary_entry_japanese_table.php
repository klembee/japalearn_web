<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictionaryEntryJapaneseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionary_entry_japanese', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('entry_id')->unsigned();
            $table->string('representation')->charset('utf16')->collation('utf16_general_ci')->nullable();
            $table->string('information')->nullable();
            $table->string('categories')->nullable();
            $table->timestamps();
        });

        Schema::table('dictionary_entry_japanese', function(Blueprint $table){
            $table->foreign('entry_id')
                ->references('id')
                ->on('dictionary_entries')
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
        Schema::table('dictionary_entry_japanese', function(Blueprint $table){
            $table->dropForeign(['entry_id']);
        });

        Schema::dropIfExists('dictionary_entry_japanese');
    }
}
