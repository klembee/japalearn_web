<?php
/**
 * Created by PhpStorm.
 * User: cleme
 * Date: 2019-10-21
 * Time: 2:08 PM
 */

namespace App\Utils;


use phpDocumentor\Reflection\Types\Integer;

class HepburnUtils
{
    /**
     * Converts a given string from romaji to hiragana
     * @param $string \String string to convert to hiragana
     * @return array The first element is true if the provided string contains only romanji. The second element is the string transformed to hiragana
     */
    static function toHiragana($string){
        $response = "";
        $ptr1 = 0;
        $ptr2 = 0;
        $i = 0;
        foreach (mb_str_split($string) as $character){
            if($i < mb_strlen($string)) {
                $window = mb_substr($string, $ptr1, $ptr2 - $ptr1 + 1);

                if (is_string($window) && $window != 'n' && array_key_exists($window, HepburnUtils::$roman_to_hiragana)) {
                    $hiragana = HepburnUtils::$roman_to_hiragana[$window];
                    $response .= $hiragana;

                    $ptr1 += strlen($window);
                    $ptr2 += 1;
                }else if(mb_substr($string, $ptr1, 1) == 'n'){
                    //Check the next character
                    if(!array_key_exists(mb_substr($string, $ptr1, 2), HepburnUtils::$roman_to_hiragana)){
                        $hiragana = HepburnUtils::$roman_to_hiragana['n'];
                        $response .= $hiragana;
                        $ptr1++;
                        $ptr2++;
                    }else{
                        $ptr2++;
                    }

                }else{
                    $ptr2++;
                }
            }
            $i++;
        }

        return array($ptr1 == $ptr2, $response);

        //Check if there are | in the response. If yes, give two answers
//        error_log($response);
//        $pipePosition = mb_strpos($response, "|");
//
//        if($pipePosition !== false){
//
//            $i = 2;
//            $before_pipe = mb_substr($response, $pipePosition - 1, 1);
//
//            if($before_pipe == 'ゃ' || $before_pipe == 'ゅ' || $before_pipe == 'ょ'){
//                $i++;
//            }
//
//            $string1 = mb_substr($response, 0, $pipePosition) . mb_substr($response, $pipePosition + $i);
//            $string2 = mb_substr($response, 0, $pipePosition - ($i - 1)) . mb_substr($response, $pipePosition + 1);
//
//            return [$string1, $string2];
//        }

//        return [$response];
    }

    private static $roman_to_hiragana = [
        'a' => 'あ',
        'i' => 'い',
        'u' => 'う',
        'e' => 'え',
        'o' => 'お',
        'ka' => 'か',
        'ga' => 'が',
        'ki' => 'き',
        'gi' => 'ぎ',
        'ku' => 'く',
        'gu' => 'ぐ',
        'ke' => 'け',
        'ge' => 'げ',
        'ko' => 'こ',
        'go' => 'ご',
        'sa' => 'さ',
        'za' => 'ざ',
        'shi' => 'し',
        'ji' => 'じ', //ぢ
        'su' => 'す',
        'zu' => 'ず', //づ
        'se' => 'せ',
        'ze' => 'ぜ',
        'so' => 'そ',
        'zo' => 'ぞ',
        'ta' => 'た',
        'da' => 'だ',
        'chi' => 'ち',
        'tsu' => 'つ',
        'te' => 'て',
        'de' => 'で',
        'to' => 'と',
        'do' => 'ど',
        'na' => 'な',
        'ni' => 'に',
        'nu' => 'ぬ',
        'ne' => 'ね',
        'no' => 'の',
        'ha' => 'は',
        'ba' => 'ば',
        'pa' => 'ぱ',
        'hi' => 'ひ',
        'bi' => 'び',
        'pi' => 'ぴ',
        'fu' => 'ふ',
        'bu' => 'ぶ',
        'pu' => 'ぷ',
        'he' => 'へ',
        'be' => 'べ',
        'pe' => 'ぺ',
        'ho' => 'ほ',
        'bo' => 'ぼ',
        'po' => 'ぽ',
        'ma' => 'ま',
        'mi' => 'み',
        'mu' => 'む',
        'me' => 'め',
        'mo' => 'も',
        'ya' => 'や',
        'yu' => 'ゆ',
        'yo' => 'よ',
        'ra' => 'ら',
        'ri' => 'り',
        'ru' => 'る',
        're' => 'れ',
        'ro' => 'ろ',
        'wa' => 'わ',
        'wo' => 'を',
        'n' => 'ん',
        //YOON
        'kya' => 'きゃ',
        'kyu' => 'きゅ',
        'kyo' => 'きょ',
        'sha' => 'しゃ',
        'shu' => 'しゅ',
        'sho' => 'しょ',
        'cha' => 'ちゃ',
        'chu' => 'ちゅ',
        'cho' => 'ちょ',
        'nya' => 'にゃ',
        'nyu' => 'にゅ',
        'nyo' => 'にょ',
        'hya' => 'ひゃ',
        'hyu' => 'ひゅ',
        'hyo' => 'ひょ',
        'mya' => 'みゃ',
        'myu' => 'みゅ',
        'myo' => 'みょ',
        'rya' => 'りゅ',
        'ryu' => 'みゅ',
        'ryo' => 'りょ',
        'gya' => 'ぎゃ',
        'gyu' => 'ぎゅ',
        'gyo' => 'ぎょ',
        'ja' => 'じゃ', //ぢゃ
        'ju' => 'じゅ', //ぢゅ
        'jo' => 'じょ', //ぢょ
        'bya' => 'びゃ',
        'byu' => 'びゅ',
        'byo' => 'びょ',
        'pya' => 'ぴゃ',
        'pyu' => 'ぴゅ',
        'pyo' => 'ぴょ',
    ];
}