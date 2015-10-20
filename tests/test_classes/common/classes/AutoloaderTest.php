<?php

use common\classes\AutoLoader;

/**
 * Class AutoLoaderTest
 *
 */
class AutoLoaderTest extends PHPUnit_Framework_TestCase {
    
    /**
     * @covers common\classes\AutoLoader::load_extension
     */
    public function test_load_extension() {
        $extensions = glob(ROOT_PATH.DS.'extensions'.DS.'*');
        foreach($extensions as $extension) {
            $name = ucwords(explode('.', basename($extension))[0]);
            self::assertTrue(AutoLoader::load_extension($name));
        }
        self::assertFalse(AutoLoader::load_extension('NotExistedClass'));

        $name = 'fakeextension';
        $extension_file = ROOT_PATH.DS.'extensions'.DS.$name;
        $extension_build_dir = ROOT_PATH.DS.'tests'.DS.'resource'.DS.$name;
        $extension_file_tar = "{$extension_file}.tar";
        $extension_file_gz = "{$extension_file}.tar.gz";

        if(file_exists($extension_file_gz)) {
            Phar::unlinkArchive($extension_file_gz);
        }
        $phar = new PharData($extension_file_tar);
        $phar->buildFromDirectory($extension_build_dir);
        $phar->compress(Phar::GZ);
        if(file_exists($extension_file_tar)) {
            unlink($extension_file_tar);
        }
        self::assertTrue(AutoLoader::load_extension($name));
        unlink($extension_file_gz);
    }

    /**
     * @covers common\classes\AutoLoader::load_extension
     */
    public function test_build_extension() {
        $extension_file = ROOT_PATH.DS.'extensions'.DS.'tools.tar.gz';
        Phar::unlinkArchive($extension_file);
        self::assertFalse(file_exists($extension_file));
        self::assertTrue(AutoLoader::load_extension('Tools'));
        self::assertTrue(file_exists($extension_file));
    }

    /**
     * @covers common\classes\AutoLoader::load_extension
     */
    public function test_changed_extension() {
        $test_file = ROOT_PATH.DS.'build'.DS.'tools'.DS.'test.php';
        file_put_contents($test_file, '');
        self::assertTrue(AutoLoader::load_extension('Tools'));
        unlink($test_file);
    }

    /**
     * @covers common\classes\AutoLoader::is_extension_changed
     */
    public function test_is_extension_changed() {
        $method = new ReflectionMethod(AutoLoader::class, 'is_extension_changed');
        $method->setAccessible(true);

        self::assertFalse($method->invoke(null, 'tools'));

        $test_file = ROOT_PATH.DS.'build'.DS.'tools'.DS.'test.php';
        if(file_exists('pfmextension://tools'.DS.'test.php')) {
            unlink('pfmextension://tools'.DS.'test.php');
        }
        file_put_contents($test_file, '');
        self::assertTrue($method->invoke(null, 'tools'));
        unlink($test_file);

        self::assertFalse($method->invoke(null, 'tools'));

        $tools_file = ROOT_PATH.DS.'build'.DS.'tools'.DS.'tools.php';
        $old_data = file_get_contents($tools_file);
        file_put_contents($tools_file, '/*test comment*/', FILE_APPEND);
        self::assertTrue($method->invoke(null, 'tools'));

        file_put_contents($tools_file, $old_data);

        self::assertFalse($method->invoke(null, 'tools'));

        $extension_file = ROOT_PATH.DS.'extensions'.DS.'tools.tar.gz';
        Phar::unlinkArchive($extension_file);
        self::assertFalse($method->invoke(null, 'tools'));
        self::assertTrue(AutoLoader::load_extension('Tools'));
    }

    /**
     * @covers common\classes\AutoLoader::load_class
     */
    public function test_load_class() {
        $base_dir = ROOT_PATH.DS.'common';
        $dirs = glob($base_dir.DS.'*');
        foreach($dirs as $dir) {
            $classes = glob($dir.DS.'*');
            foreach($classes as $class) {
                $name = ucwords(explode('.', basename($class))[0]);
                $name = basename($base_dir).'\\'.basename($dir).'\\'.$name;
                self::assertTrue(AutoLoader::load_class($name));
            }
        }
        self::assertFalse(AutoLoader::load_class('NotExistedClass'));
    }
}
