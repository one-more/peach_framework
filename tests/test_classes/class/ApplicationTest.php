<?php

class ApplicationTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers Application::load_extension
	 */
	public function test_load_extension() {
		$extensions = glob(ROOT_PATH.DS.'extensions'.DS.'*');
		foreach($extensions as $extension) {
			$name = ucwords(explode('.', basename($extension))[0]);
			$this->assertTrue(Application::load_extension($name));
		}
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
	}

	/**
	 * @covers Application::get_class
	 */
	public function test_get_class() {
		$this->assertInternalType('object', Application::get_class('System'));
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
}