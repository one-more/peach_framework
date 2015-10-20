<?php

use common\helpers\FileSystemHelper;

class FileSystemHelperTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers FileSystemHelper::remove_dir
     */
    public function test_remove_dir() {
        $test_dir = ROOT_PATH.DS.'tests'.DS.uniqid('dir_to_remove', true);
        mkdir($test_dir);
        mkdir($test_dir.DS.'dir1');
        file_put_contents($test_dir.DS.'dir1'.DS.'file.txt', '');
        self::assertTrue(is_dir($test_dir));
        FileSystemHelper::remove_dir($test_dir);
        self::assertFalse(is_dir($test_dir));
    }
}
