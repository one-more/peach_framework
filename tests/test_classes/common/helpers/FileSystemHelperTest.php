<?php

use common\helpers\FileSystemHelper;

class FileSystemHelperTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers common\helpers\FileSystemHelper::init_dirs
     */
    public function test_init_dirs() {
        FileSystemHelper::init_dirs();
    }

    /**
     * @covers common\helpers\FileSystemHelper::remove_dir
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

    /**
     * @covers common\helpers\FileSystemHelper::copy_dir
     */
    public function test_copy_dir() {
        $source = ROOT_PATH.DS.'tests'.DS.uniqid('dir_to_copy', true);
        $dest = ROOT_PATH.DS.'tests'.DS.'copied_dir';
        $file = uniqid('test_file', true);
        $sub_dir = 'sub_dir1';
        $sub_file = uniqid('test_sub_file', true);

        mkdir($source, 0755, true);
        file_put_contents($source.DS.$file, '');
        mkdir($source.DS.$sub_dir, 0755);
        file_put_contents($source.DS.$sub_dir.DS.$sub_file, '');

        FileSystemHelper::copy_dir($source, $dest);

        self::assertTrue(is_dir($dest));
        self::assertTrue(file_exists($dest.DS.$file));
        self::assertTrue(is_dir($dest.DS.$sub_dir));
        self::assertTrue(file_exists($dest.DS.$sub_dir.DS.$sub_file));

        FileSystemHelper::remove_dir($source);
        FileSystemHelper::remove_dir($dest);

        self::assertFalse(is_dir($source));
        self::assertFalse(is_dir($dest));
    }
}
