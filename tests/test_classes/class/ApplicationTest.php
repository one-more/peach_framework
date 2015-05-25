<?php

class ApplicationTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		if(!in_array('pfmextension', stream_get_wrappers())) {
			stream_wrapper_register("pfmextension", "PFMExtensionWrapper");
		}
	}

	/**
	 * @covers Application::load_extension
	 */
	public function test_load_extension() {
		$extensions = glob(ROOT_PATH.DS.'extensions'.DS.'*');
		foreach($extensions as $extension) {
			$name = ucwords(explode('.', basename($extension))[0]);
			$this->assertTrue(Application::load_extension($name));
		}
		$this->assertFalse(Application::load_extension('NotExistedClass'));
	}

	/**
	 * @covers Application::load_extension
	 */
	public function test_build_extension() {
		$extension_file = ROOT_PATH.DS.'extensions'.DS.'paginator.tar.gz';
		Phar::unlinkArchive($extension_file);
		$this->assertFalse(file_exists($extension_file));
		$this->assertTrue(Application::load_extension('Paginator'));
		$this->assertTrue(file_exists($extension_file));
	}

	/**
	 * @covers Application::load_extension
	 */
	public function test_changed_extension() {
		$test_file = ROOT_PATH.DS.'build'.DS.'paginator'.DS.'test.php';
		file_put_contents($test_file, '');
		$this->assertTrue(Application::load_extension('Paginator'));
		unlink($test_file);
	}

	/**
	 * @covers Application::is_extension_changed
	 */
	public function test_is_extension_changed() {
		$method = new ReflectionMethod('Application', 'is_extension_changed');
		$method->setAccessible(true);

		$this->assertFalse($method->invoke(null, 'paginator'));

		$test_file = ROOT_PATH.DS.'build'.DS.'paginator'.DS.'test.php';
		if(file_exists('pfmextension://paginator'.DS.'test.php')) {
			unlink('pfmextension://paginator'.DS.'test.php');
		}
		file_put_contents($test_file, '');
		$this->assertTrue($method->invoke(null, 'paginator'));
		unlink($test_file);

		$this->assertFalse($method->invoke(null, 'paginator'));

		$paginator_file = ROOT_PATH.DS.'build'.DS.'paginator'.DS.'paginator.php';
		$old_data = file_get_contents($paginator_file);
		file_put_contents($paginator_file, '/*test comment*/', FILE_APPEND);
		$this->assertTrue($method->invoke(null, 'paginator'));

		file_put_contents($paginator_file, $old_data);

		$this->assertFalse($method->invoke(null, 'paginator'));

		$extension_file = ROOT_PATH.DS.'extensions'.DS.'paginator.tar.gz';
		Phar::unlinkArchive($extension_file);
		$this->assertFalse($method->invoke(null, 'paginator'));
		$this->assertTrue(Application::load_extension('Paginator'));
	}

	/**
	 * @covers Application::load_class
	 */
	public function test_load_class() {
		$classes = glob(ROOT_PATH.DS.'class'.DS.'*');
		foreach($classes as $class) {
			$name = ucwords(explode('.', basename($class))[0]);
			$this->assertTrue(Application::load_class($name));
		}
		$this->assertFalse(Application::load_class('NotExistedClass'));
	}

	/**
	 * @covers Application::load_trait
	 */
	public function test_load_trait() {
		$traits = glob(ROOT_PATH.DS.'traits'.DS.'*');
		foreach($traits as $trait) {
			$name = explode('.', basename($trait))[0];
			$this->assertTrue(Application::load_trait($name));
		}
		$this->assertFalse(Application::load_trait('NotExistedTrait'));
	}

	/**
	 * @covers Application::load_template
	 */
	public function test_load_template() {
		$templates = glob(ROOT_PATH.DS.'templates'.DS.'*');
		foreach($templates as $template) {
			$name = basename($template);
			$this->assertTrue(Application::load_template($name));
		}
		$this->assertFalse(Application::load_template('NotExistedClass'));
	}

	/**
	 * @covers Application::load_interface
	 */
	public function test_load_interface() {
		$interfaces = glob(ROOT_PATH.DS.'interface'.DS.'*');
		foreach($interfaces as $interface) {
			$name = ucwords(explode('.', basename($interface))[0]);
			$this->assertTrue(Application::load_interface($name));
		}
		$this->assertFalse(Application::load_interface('NotExistedInterface'));
	}

	/**
	 * @covers Application::load_exception
	 */
	public function test_load_exception() {
		$exceptions = glob(ROOT_PATH.DS.'exception'.DS.'*');
		foreach($exceptions as $exception) {
			$name = ucwords(explode('.', basename($exception))[0]);
			$this->assertTrue(Application::load_exception($name));
		}
		$this->assertFalse(Application::load_exception('NotExistedException'));
	}

	/**
	 * @covers Application::get_class
	 */
	public function test_get_class() {
		$this->assertInternalType('object', Application::get_class('User'));

		$property = new ReflectionProperty('Application', 'instances');
		$property->setAccessible(true);
		$property->setValue(null, []);
		$this->assertInternalType('object', Application::get_class('User'));
	}

	/**
	 * @covers Application::remove_dir
	 */
	public function test_remove_dir() {
		$test_dir = ROOT_PATH.DS.'tests'.DS.'dir_to_remove';
		mkdir($test_dir);
		mkdir($test_dir.DS.'dir1');
		file_put_contents($test_dir.DS.'dir1'.DS.'file.txt', '');
		$this->assertTrue(is_dir($test_dir));
		Application::remove_dir($test_dir);
		$this->assertFalse(is_dir($test_dir));
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @covers Application::get_class
	 */
	public function test_get_not_existed_class() {
		$this->assertInternalType('object', Application::get_class('NotExistedClass'));
	}

	/**
	 * @covers Application::return_bytes
	 */
	public function test_return_bytes() {
		$this->assertEquals(Application::return_bytes('10kb'), 10240);
		$this->assertEquals(Application::return_bytes('10.5kb'), 10752);
		$this->assertEquals(Application::return_bytes('10mb'), 1024*1024*10);
		$this->assertEquals(Application::return_bytes('10gb'), 1024*1024*1024*10);
		$this->assertEquals(Application::return_bytes('10'), 10);
		$this->assertEquals(Application::return_bytes('10b'), 10);
		$this->assertEquals(Application::return_bytes('10pv'), null);
		$this->assertEquals(Application::return_bytes('fdspv'), null);
	}

	/**
	 * @covers Application::is_assoc_array
	 */
	public function test_is_assoc_array() {
		$this->assertTrue(Application::is_assoc_array(['a'=>'b', 'c'=>'d']));
		$this->assertFalse(Application::is_assoc_array([1,2,3]));
		$this->assertTrue(Application::is_assoc_array([1,2,3,'a'=>'b']));
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @covers Application::is_assoc_array
	 */
	public function test_is_assoc_array_with_no_array() {
		Application::is_assoc_array(1);
	}

	/**
	 * @covers Application::is_dev
	 */
	public function test_is_dev() {
		$this->assertTrue(Application::is_dev());

		$_SERVER['REMOTE_ADDR'] = '';
		$_SERVER['HTTP_HOST'] = '';
		$this->assertFalse(Application::is_dev());

		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		$this->assertTrue(Application::is_dev());

		$_SERVER['REMOTE_ADDR'] = '';
		$_SERVER['HTTP_HOST'] = 'dev.pfm.my';
		$this->assertTrue(Application::is_dev());
	}
}