<?php

/**
 * Class AutoloaderTest
 *
 * @method bool assertFalse($var)
 * @method bool assertTrue($var)
 */
class AutoloaderTest extends PHPUnit_Framework_TestCase {
    
    /**
     * @covers Autoloader::load_extension
     */
    public function test_load_extension() {
        $extensions = glob(ROOT_PATH.DS.'extensions'.DS.'*');
        foreach($extensions as $extension) {
            $name = ucwords(explode('.', basename($extension))[0]);
            $this->assertTrue(Autoloader::load_extension($name));
        }
        $this->assertFalse(Autoloader::load_extension('NotExistedClass'));
    }

    /**
     * @covers Autoloader::load_extension
     */
    public function test_build_extension() {
        $extension_file = ROOT_PATH.DS.'extensions'.DS.'tools.tar.gz';
        Phar::unlinkArchive($extension_file);
        $this->assertFalse(file_exists($extension_file));
        $this->assertTrue(Autoloader::load_extension('Tools'));
        $this->assertTrue(file_exists($extension_file));
    }

    /**
     * @covers Autoloader::load_extension
     */
    public function test_changed_extension() {
        $test_file = ROOT_PATH.DS.'build'.DS.'tools'.DS.'test.php';
        file_put_contents($test_file, '');
        $this->assertTrue(Autoloader::load_extension('Tools'));
        unlink($test_file);
    }

    /**
     * @covers Autoloader::is_extension_changed
     */
    public function test_is_extension_changed() {
        $method = new ReflectionMethod('Autoloader', 'is_extension_changed');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke(null, 'tools'));

        $test_file = ROOT_PATH.DS.'build'.DS.'tools'.DS.'test.php';
        if(file_exists('pfmextension://tools'.DS.'test.php')) {
            unlink('pfmextension://tools'.DS.'test.php');
        }
        file_put_contents($test_file, '');
        $this->assertTrue($method->invoke(null, 'tools'));
        unlink($test_file);

        $this->assertFalse($method->invoke(null, 'tools'));

        $tools_file = ROOT_PATH.DS.'build'.DS.'tools'.DS.'tools.php';
        $old_data = file_get_contents($tools_file);
        file_put_contents($tools_file, '/*test comment*/', FILE_APPEND);
        $this->assertTrue($method->invoke(null, 'tools'));

        file_put_contents($tools_file, $old_data);

        $this->assertFalse($method->invoke(null, 'tools'));

        $extension_file = ROOT_PATH.DS.'extensions'.DS.'tools.tar.gz';
        Phar::unlinkArchive($extension_file);
        $this->assertFalse($method->invoke(null, 'tools'));
        $this->assertTrue(Autoloader::load_extension('Tools'));
    }

    /**
     * @covers Autoloader::load_class
     */
    public function test_load_class() {
        $classes = glob(ROOT_PATH.DS.'class'.DS.'*');
        foreach($classes as $class) {
            $name = ucwords(explode('.', basename($class))[0]);
            $this->assertTrue(Autoloader::load_class($name));
        }
        $this->assertFalse(Autoloader::load_class('NotExistedClass'));
    }

    /**
     * @covers Autoloader::load_trait
     */
    public function test_load_trait() {
        $traits = glob(ROOT_PATH.DS.'traits'.DS.'*');
        foreach($traits as $trait) {
            $name = explode('.', basename($trait))[0];
            $this->assertTrue(Autoloader::load_trait($name));
        }
        $this->assertFalse(Autoloader::load_trait('NotExistedTrait'));
    }

    /**
     * @covers Autoloader::load_template
     */
    public function test_load_template() {
        $templates = glob(ROOT_PATH.DS.'templates'.DS.'*');
        foreach($templates as $template) {
            $name = basename($template);
            $this->assertTrue(Autoloader::load_template($name));
        }
        $this->assertFalse(Autoloader::load_template('NotExistedClass'));
    }

    /**
     * @covers Autoloader::load_interface
     */
    public function test_load_interface() {
        $interfaces = glob(ROOT_PATH.DS.'interface'.DS.'*');
        foreach($interfaces as $interface) {
            $name = ucwords(explode('.', basename($interface))[0]);
            $this->assertTrue(Autoloader::load_interface($name));
        }
        $this->assertFalse(Autoloader::load_interface('NotExistedInterface'));
    }

    /**
     * @covers Autoloader::load_exception
     */
    public function test_load_exception() {
        $exceptions = glob(ROOT_PATH.DS.'exception'.DS.'*');
        foreach($exceptions as $exception) {
            $name = ucwords(explode('.', basename($exception))[0]);
            $this->assertTrue(Autoloader::load_exception($name));
        }
        $this->assertFalse(Autoloader::load_exception('NotExistedException'));
    }
}
