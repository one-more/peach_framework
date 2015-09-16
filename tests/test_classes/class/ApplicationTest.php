<?php

/**
 * Class AnnotationDecorated
 *
 * @decorate AnnotationsDecorator
 */
class AnnotationDecorated {}

class NotDecorated {}

class ApplicationTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		if(!in_array('pfmextension', stream_get_wrappers())) {
			stream_wrapper_register('pfmextension', 'PFMExtensionWrapper');
		}
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
     * @covers Application::extension_exists
     */
	public function test_extension_exists() {
        $this->assertTrue(Application::extension_exists('system'));

        $this->assertTrue(Application::extension_exists('smarty'));
    }

    /**
     * @covers Application::handle_annotations
     */
    public function test_handle_annotations() {
        $obj = new AnnotationDecorated();
        $annotations = ReflectionHelper::get_class_annotations($obj);
        $method = new ReflectionMethod('Application', 'handle_annotations');
        $method->setAccessible(true);
        $this->assertTrue($method->invoke(null, $obj, $annotations) instanceof AnnotationsDecorator);

        $this->assertTrue($method->invoke(null, new NotDecorated(), $annotations) instanceof NotDecorated);
    }

	/**
	 * @expectedException InvalidArgumentException
	 * @covers Application::get_class
	 */
	public function test_get_not_existed_class() {
		$this->assertInternalType('object', Application::get_class('NotExistedClass'));
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

    /**
     * @covers Application::init_validator
     */
    public function test_init_validator() {
        Application::init_validator();
    }
}