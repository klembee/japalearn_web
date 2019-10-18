<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictionaryMeaning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionary_meaning', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('entry_id')->unsigned();
            $table->text('meaning')->charset('utf16')->collation('utf16_general_ci')->nullable();
            $table->string('applies_to')->charset('utf16')->collation('utf16_general_ci')->nullable();
            $table->string('see_also')->charset('utf16')->collation('utf16_general_ci')->nullable();
            $table->string('antonym')->charset('utf16')->collation('utf16_general_ci')->nullable();
            $table->string('types')->nullable();
            $table->string('field')->nullable();
            $table->string('misc')->nullable();
            $table->string('dialect')->nullable();
            $table->string('information')->nullable();
            $table->timestamps();
        });

        Schema::table('dictionary_meaning', function(Blueprint $table) {
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
        Schema::table('dictionary_meaning', function(Blueprint $table){
            $table->dropForeign(['entry_id']);
        });
        Schema::dropIfExists('dictionary_meaning');
    }
}
