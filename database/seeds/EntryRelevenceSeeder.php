<?php


use App\Models\DictionaryEntry;
use App\Models\Kanji;
use App\Utils\UnicodeUtil;
use Illuminate\Support\Facades\DB;

class EntryRelevenceSeeder extends \Illuminate\Database\Seeder
{
    public function run(){

        $number = DB::table('dictionary_entries')->count();
        $i = 0;

        $entries = DB::table('dictionary_entries')->orderBy('id')->chunk(100, function($entries) use($number){
            foreach ($entries as $entry) {
                $japanese_representation = DB::table('dictionary_entry_japanese')->where('entry_id', $entry->id)->first();
                if (isset($japanese_representation)) {

                    $representation = $japanese_representation->representation;
                    $representation_array = [];
                    $length = mb_strlen($representation);
                    for($i = 0 ; $i < $length ; $i++){
                        $representation_array[] = mb_substr($representation, $i, 1, 'UTF-8');
                    }

                    $total_relevence = 0;
                    foreach ($representation_array as $character) {

                        if(IntlChar::ord($character) < UnicodeUtil::$KANJI_UNICODE_START) continue; // Not a kanji character

                        $kanji = DB::table('kanjis')->where('literal', $character)->first();
//                        $this->command->info("ASDF: " .$representation_array[0]);
                        if (isset($kanji)) {
                            $relevence = 0;
                            if(isset($kanji->frequency)){
                                //$relevence += (1 / $kanji->frequency) * 1000;
                            }
                            if(isset($kanji->grade)){
                                $relevence += 500;
                            }
                            if(isset($kanji->jlpt_level)){
                                $relevence += 1000;
                            }
                            $relevence = round($relevence);

                            if($relevence > $total_relevence){
                                $total_relevence = $relevence;
                            }
                        }
                    }
                    $a = DictionaryEntry::query()->find($entry->id);
                    $a->relevence = $total_relevence;
                    $a->save();
//                    $this->command->info($representation. ". Relevence: " . $total_relevence);
                }
            }
            global $i;
            $i += 100;
            $this->command->info(($i / $number) * 100 . "%");
        });
    }
}