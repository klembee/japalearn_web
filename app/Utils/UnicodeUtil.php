<?php
namespace App\Utils;

use IntlChar;

/**
 * Utility for unicodes characters
 * @author Clement Bisaillon
 */
class UnicodeUtil{
    public static $KANJI_UNICODE_START = 19968;
    public static $KANJI_UNICODE_END = 40895;
    public static $HIRAGANA_UNICODE_START = 12352;
    public static $HIRAGANA_UNICODE_END = 12447;
    public static $KATAKANA_UNICODE_START = 12448;
    public static $KATAKANA_UNICODE_END = 12543;

    /**
     * Checks if the given string contains only japanese characters
     * @param $string string the String to check
     * @return bool true if the string contains only japanese characters, alse otherwise
     */
    public static function isOnlyJapaneseChars($string){
        foreach (mb_str_split($string) as $character){
            if(IntlChar::ord($character) < UnicodeUtil::$HIRAGANA_UNICODE_START){
                return false;
            }
        }
        return true;
    }

}