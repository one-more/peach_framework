<?php

class StringHelperTest extends PHPUnit_Framework_TestCase {
    
    /**
     * @covers StringHelper::return_bytes
     */
    public function test_return_bytes() {
        $this->assertEquals(StringHelper::return_bytes('10kb'), 10240);
        $this->assertEquals(StringHelper::return_bytes('10.5kb'), 10752);
        $this->assertEquals(StringHelper::return_bytes('10mb'), 1024*1024*10);
        $this->assertEquals(StringHelper::return_bytes('10gb'), 1024*1024*1024*10);
        $this->assertEquals(StringHelper::return_bytes('10'), 10);
        $this->assertEquals(StringHelper::return_bytes('10b'), 10);
        $this->assertEquals(StringHelper::return_bytes('10pv'), null);
        $this->assertEquals(StringHelper::return_bytes('fdspv'), null);
    }
}
