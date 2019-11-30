<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLexicMeaningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lexic_meaning', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lexic_id', false, true);
            $table->string('meaning');
            $table->timestamps();
        });

        Schema::table('lexic_meaning', function(Blueprint $table){
            $table->foreign('lexic_id')
                ->references('id')
                ->on('lexic')
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
        Schema::table('lexic_meaning', function(Blueprint $table){
            $table->dropForeign(['lexic_id']);
        });
        Schema::dropIfExists('lexic_meaning');
    }
}
