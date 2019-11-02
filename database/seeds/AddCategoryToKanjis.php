<?php

use Illuminate\Support\Facades\DB;

class AddCategoryToKanjis extends \Illuminate\Database\Seeder
{
    public function run(){
        $categories = fopen("resources/kanjis/categories.txt", "r") or die ("Unable to read categories file");

        while(!feof($categories)){
            $line = fgets($categories);

            $kanji = explode(",", $line)[0];
            $category = explode(",", $line)[1];

            //find the kanji
            $kanjiRow = \App\Models\Kanji::query()->where('literal', $kanji)->first();
            if($kanjiRow){
                $category = str_replace(["\n", "\r"], "", $category);
                $kanjiRow->category = $category;
                $kanjiRow->save();
            }
        }

        fclose($categories);
    }
}