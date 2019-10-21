<?php

namespace Tests\Unit;

use App\Utils\UnicodeUtil;
use Tests\TestCase;

class UnicodeTest extends TestCase
{

    public function test_basic_transform_to_hiragana_from_romanji()
    {
        $source = "いとこたちは";
        $this->assertTrue(UnicodeUtil::isOnlyJapaneseChars($source));

        $source = "いとdこたちは";
        $this->assertFalse(UnicodeUtil::isOnlyJapaneseChars($source));

        $source = "いとこた住は";
        $this->assertTrue(UnicodeUtil::isOnlyJapaneseChars($source));

        $source = "じゅう";
        $this->assertTrue(UnicodeUtil::isOnlyJapaneseChars($source));
    }
}
