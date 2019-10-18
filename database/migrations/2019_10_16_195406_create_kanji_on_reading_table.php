<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKanjiOnReadingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kanji_on_reading', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('kanji_id')->unsigned();
            $table->string('reading');
            $table->timestamps();
        });

        //Setup foreign
        Schema::table('kanji_on_reading', function (Blueprint $table) {
            $table->foreign('kanji_id')
                ->references('id')
                ->on('kanjis')
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
        Schema::table('kanji_on_reading', function (Blueprint $table){
            $table->dropForeign(['kanji_id']);
        });
        Schema::dropIfExists('kanji_on_reading');
    }
}
