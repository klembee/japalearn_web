<?php

use Illuminate\Support\Facades\DB;

/**
 * Generates the dictionnary and gloss table
 */
class DictionarySeeder extends \Illuminate\Database\Seeder
{
    public function run(){
        //todo: change to XMLReader() to use less memory
        $dic_data = simplexml_load_file("resources/kanjis/JMdict_e.xml")->children() or die("Failed to load the JMdict_e file");

        $entries = $dic_data->entry;
        $nb_entries = count($entries);
        $index = 0;

        foreach ($entries as $entry){

            $ent_seq = $entry->ent_seq;
            $k_ele = $entry->k_ele;
            $r_ele = $entry->r_ele;

            $entry_id = DB::table('dictionary_entries')->insertGetId([
                'ent_seq' => $ent_seq,
            ]);

            //Create the japanese representations
            foreach ($k_ele as $k){
                $representation = $k->keb;
                $information = $k->ke_inf;
                $categories = $k->children()->ke_pri;
                $category = "";
                foreach ($categories as $c){
                    $category .= ($c . ";");
                }
                $category = substr($category, 0, strlen($category) - 1);

                DB::table('dictionary_entry_japanese')->insert([
                    'entry_id' => $entry_id,
                    'representation' => $representation,
                    'information' => $information,
                    'categories' => $category,
                ]);
            }

            //Create the kana representations
            foreach ($r_ele as $r){
                $representation = $r->reb;
                $information = $r->re_inf;
                $true_reading = true;
                if(isset($r->re_nokanji)){
                    $true_reading = false;
                }

                $categories = $r->children()->re_pri;
                $category = "";
                foreach ($categories as $c){
                    $category .= ($c . ";");
                }
                $category = substr($category, 0, strlen($category) - 1);

                $kana_id = DB::table('dictionary_entry_kana')->insertGetId([
                    'entry_id' => $entry_id,
                    'representation' => $representation,
                    'true_reading' => $true_reading,
                    'information' => $information,
                    'categories' => $category,
                ]);

                if(isset($r->re_restr)){
                    $re_restr = $r->re_restr;
                    //Find the japanese entry
                    $japan_entry = DB::table('dictionary_entry_japanese')->where('representation', $re_restr)->get()[0];

                    DB::table('dictionary_entry_kana_japanese')->insert([
                        'kana_id' => $kana_id,
                        'japanese_id' => $japan_entry->id,
                    ]);
                }

            }

            //Create the meanings

            $senses = $entry->children()->sense;
            foreach ($senses as $sense){
                $meaning = "";
                foreach ($sense->children()->gloss as $gloss){
                    $meaning .= ($gloss . ';');
                }
                if(strlen($meaning) > 0){
                    $meaning = substr($meaning, 0, strlen($meaning) - 1);
                }

                $applies_to = "";
                $stagks = $sense->children()->stagk;
                $stagrs = $sense->children()->stagrs;
                foreach ($stagks as $stagk){
                    $applies_to .= ($stagk . ";");
                }
                foreach ($stagrs as $stagr){
                    $applies_to .= ($stagr . ";");
                }
                if(strlen($applies_to) > 0){
                    $applies_to = substr($applies_to, 0, strlen($applies_to) - 1);
                }

                $see_also = $sense->xref;
                $antonym = $sense->ant;

                $types = "";
                $poss = $sense->children()->pos;
                foreach ($poss as $pos){
                    $types .= ($pos . ';');
                }

                $field = $sense->field;
                $misc = $sense->misc;
                $dialect = $sense->dial;
                $information = $sense->s_inf;

                DB::table('dictionary_meaning')->insert([
                    'entry_id' => $entry_id,
                    'meaning' => $meaning,
                    'applies_to' => $applies_to,
                    'see_also' => $see_also,
                    'antonym' => $antonym,
                    'types' => $types,
                    'field' => $field,
                    'misc' => $misc,
                    'dialect' => $dialect,
                    'information' => $information
                ]);
            }

            //Show progress
            if($index % 50 == 0) {
                $this->command->info((($index / $nb_entries) * 100) . " / 100");
            }
            $index++;
        }
    }
}