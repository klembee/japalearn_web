<?php

use Illuminate\Support\Facades\DB;

/**
 * Class KanjiTableSeeder
 * Seeds the kanji table from the kanjidic2.xml file
 * @author Clement Bisaillon
 */
class KanjiTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $kanji_data = simplexml_load_file("resources/kanjis/kanjidic2.xml")->children() or die("Failed to load the kanjidic2 file");

        $characters = $kanji_data->character;
        $nb_chars = count($characters);
        $index = 0;

        foreach ($characters as $character) {
            $literal = $character->literal;
            $grade = $character->misc->children()->grade;
            $stroke_count = $character->misc->children()->stroke_count;
            $frequency = $character->misc->children()->freq;
            $jlpt_level = $character->misc->children()->jlpt;

            try {
                //Create the kanji in the database
                $kanji_id = DB::table('kanjis')->insertGetId([
                    'literal' => $literal,
                    'grade' => $grade == '' ? null : $grade,
                    'stroke_count' => $stroke_count,
                    'frequency' => $frequency == '' ? null : $frequency,
                    'jlpt_level' => $jlpt_level == '' ? null : $jlpt_level
                ]);
            }catch(Exception $e){
                $this->command->info("Error at kanji: $literal");
            }


            if (isset($character->reading_meaning)) {
                $readings = $character->reading_meaning->children()->rmgroup->reading;
                foreach ($readings as $reading) {
                    if ($reading['r_type'] == 'ja_on') {
                    DB::table('kanji_on_reading')->insert([
                        'kanji_id' => $kanji_id,
                        'reading' => $reading
                    ]);
                    } else if ($reading['r_type'] == 'ja_kun') {
                    DB::table('kanji_kun_reading')->insert([
                        'kanji_id' => $kanji_id,
                        'reading' => $reading
                    ]);
                    }
                }

                $meanings_en = $character->reading_meaning->children()->rmgroup->xpath("meaning[not(@m_lang)]");
                foreach ($meanings_en as $key => $meaning) {
                    DB::table('kanji_meaning')->insert([
                        'kanji_id' => $kanji_id,
                        'meaning' => $meaning
                    ]);
                }
            }

            //Show progress
            if($index % 50 == 0) {
                $this->command->info((($index / $nb_chars) * 100) . " / 100");
            }
            $index++;
        }

    }
}