<?php

use common\helpers\StringHelper;

class StringHelperTest extends PHPUnit_Framework_TestCase {
    
    /**
     * @covers common\helpers\StringHelper::return_bytes
     */
    public function test_return_bytes() {
        self::assertEquals(StringHelper::return_bytes('10kb'), 10240);
        self::assertEquals(StringHelper::return_bytes('10.5kb'), 10752);
        self::assertEquals(StringHelper::return_bytes('10mb'), 1024*1024*10);
        self::assertEquals(StringHelper::return_bytes('10gb'), 1024*1024*1024*10);
        self::assertEquals(StringHelper::return_bytes('10'), 10);
        self::assertEquals(StringHelper::return_bytes('10b'), 10);
        self::assertEquals(StringHelper::return_bytes('10pv'), null);
        self::assertEquals(StringHelper::return_bytes('fdspv'), null);
    }

    /**
     * @covers common\helpers\StringHelper::camelcase_to_dash
     */
    public function test_camelcase_to_dash() {
        self::assertEquals('starter/admin_panel', StringHelper::camelcase_to_dash('Starter/AdminPanel'));
    }
}
