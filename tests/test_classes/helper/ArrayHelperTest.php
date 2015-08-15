<?php

class ArrayHelperTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers ArrayHelper::is_assoc_array
     */
    public function test_is_assoc_array() {
        $this->assertTrue(ArrayHelper::is_assoc_array(['a'=>'b', 'c'=>'d']));
        $this->assertFalse(ArrayHelper::is_assoc_array([1,2,3]));
        $this->assertTrue(ArrayHelper::is_assoc_array([1,2,3,'a'=>'b']));
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers ArrayHelper::is_assoc_array
     */
    public function test_is_assoc_array_with_no_array() {
        ArrayHelper::is_assoc_array(1);
    }
}
