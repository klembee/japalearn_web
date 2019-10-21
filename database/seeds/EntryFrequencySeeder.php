<?php


use App\Models\DictionaryEntry;
use App\Models\Kanji;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EntryFrequencySeeder extends \Illuminate\Database\Seeder
{
    public function run(){

        $frequency_file = fopen('resources/kanjis/frequency_list.txt', "r") or die("Cant open frequency file");

        $i = 1;
        while(!feof($frequency_file)){
            $word = fgets($frequency_file);

            $word = trim($word);

            //Find the word in the database
            $entry = DictionaryEntry::query()->whereHas('japanese_representations', function(Builder $query) use($word){
                $query->where('representation', $word);
            })->first();

            if(isset($entry)){
                $entry->update(['frequency' => $i]);
            }



            $this->command->info($i);
            $i++;
        }

        fclose($frequency_file);
    }
}