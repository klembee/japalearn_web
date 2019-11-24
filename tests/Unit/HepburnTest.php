<?php

namespace Tests\Unit;

use App\Utils\HepburnUtils;
use Tests\TestCase;

/**
 * Tests the conversion from romanji to hiragana
 */
class HepburnTest extends TestCase
{

    public function test_basic_transform_to_hiragana_from_romanji()
    {
        $source = "watashi";
        $this->assertEquals([true, 'わたし'], HepburnUtils::toHiragana($source));

        $source = "nihongo";
        $this->assertEquals([true,'にほんご'], HepburnUtils::toHiragana($source));

        $source = "gomenasai";
        $this->assertEquals([true,'ごめなさい'], HepburnUtils::toHiragana($source));

        $source = "honjitsu";
        $this->assertEquals([true,'ほんじつ'], HepburnUtils::toHiragana($source));

        $source = "utsuwo";
        $this->assertEquals([true,'うつを'], HepburnUtils::toHiragana($source));

        $source = "nihongo";
        $this->assertEquals([true,'にほんご'], HepburnUtils::toHiragana($source));

        $source = "kuu";
        $this->assertEquals([true,'くう'], HepburnUtils::toHiragana($source));

        $source = "ain";
        $this->assertEquals([true, 'あいん'], HepburnUtils::toHiragana($source));

        $source = "saikin";
        $this->assertEquals([true, 'さいきん'], HepburnUtils::toHiragana($source));
    }

    public function test_yoon_transform_to_hiragana_from_romanji(){
        $source = "gyaku";
        $this->assertEquals([true,'ぎゃく'], HepburnUtils::toHiragana($source));

        $source = "juu";
        $this->assertEquals([true,'じゅう'], HepburnUtils::toHiragana($source));

        $source = "pyajuju";
        $this->assertEquals([true,'ぴゃじゅじゅ'], HepburnUtils::toHiragana($source));
    }

    public function test_contains_only_romanji(){
        $source = "hakunamatata";
        $this->assertTrue(HepburnUtils::toHiragana($source)[0]);

        $source = "hakunzzamatata";
        $this->assertFalse(HepburnUtils::toHiragana($source)[0]);

        $source = "Good morning";
        $this->assertFalse(HepburnUtils::toHiragana($source)[0]);
    }

    public function test_contains_japanese_char(){
        $source = "あさ";
        $this->assertEquals([true, 'あさ'], HepburnUtils::toHiragana($source));

        $source = "あsa";
        $this->assertEquals([true, 'あさ'], HepburnUtils::toHiragana($source));
    }
}
