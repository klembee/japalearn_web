<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetEntryFrequencyDefaultNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dictionary_entries', function(Blueprint $table){
            $table->integer('frequency')->unsigned()->nullable()->default('null')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dictionary_entries', function(Blueprint $table){
            $table->integer('frequency')->unsigned()->change();
        });
    }
}
