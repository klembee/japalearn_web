<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKanjiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kanjis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('literal')->charset('utf16')->collation('utf16_general_ci');
            $table->integer('grade')->nullable();
            $table->integer('stroke_count');
            $table->integer('frequency')->nullable();
            $table->integer('jlpt_level')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kanjis');
    }
}
