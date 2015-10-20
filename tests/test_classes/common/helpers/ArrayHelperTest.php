<?php

use common\helpers\ArrayHelper;

class ArrayHelperTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers ArrayHelper::is_assoc_array
     */
    public function test_is_assoc_array() {
        self::assertTrue(ArrayHelper::is_assoc_array(['a'=>'b', 'c'=>'d']));
        self::assertFalse(ArrayHelper::is_assoc_array([1,2,3]));
        self::assertTrue(ArrayHelper::is_assoc_array([1,2,3,'a'=>'b']));
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers ArrayHelper::is_assoc_array
     */
    public function test_is_assoc_array_with_no_array() {
        ArrayHelper::is_assoc_array(1);
    }
}
