<?php

use common\decorators\AnnotationsDecorator;
use common\classes\Application;
use common\helpers\ReflectionHelper;

/**
 * Class AnnotationDecorated
 *
 * @decorate \common\decorators\AnnotationsDecorator
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
	 * @covers common\classes\Application::get_class
	 */
	public function test_get_class() {
		self::assertInternalType('object', Application::get_class('User'));

		$property = new ReflectionProperty(Application::class, 'instances');
		$property->setAccessible(true);
		$property->setValue(null, []);
		self::assertInternalType('object', Application::get_class('User'));
	}

    /**
     * @covers common\classes\Application::extension_exists
     */
	public function test_extension_exists() {
        self::assertTrue(Application::extension_exists('system'));

        self::assertFalse(Application::extension_exists('smarty'));
    }

    /**
     * @covers common\classes\Application::handle_annotations
     */
    public function test_handle_annotations() {
        $obj = new AnnotationDecorated();
        $annotations = ReflectionHelper::get_class_annotations($obj);
        $method = new ReflectionMethod(Application::class, 'handle_annotations');
        $method->setAccessible(true);
        self::assertTrue($method->invoke(null, $obj, $annotations) instanceof AnnotationsDecorator);

		$obj = new NotDecorated();
        $annotations = ReflectionHelper::get_class_annotations($obj);
        self::assertTrue($method->invoke(null, $obj, $annotations) instanceof NotDecorated);
    }

	/**
	 * @expectedException InvalidArgumentException
	 * @covers common\classes\Application::get_class
	 */
	public function test_get_not_existed_class() {
		self::assertInternalType('object', Application::get_class('NotExistedClass'));
	}

	/**
	 * @covers common\classes\Application::is_dev
	 */
	public function test_is_dev() {
		self::assertTrue(Application::is_dev());

		$_SERVER['REMOTE_ADDR'] = '';
		$_SERVER['HTTP_HOST'] = '';
		self::assertFalse(Application::is_dev());

		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		self::assertTrue(Application::is_dev());

		$_SERVER['REMOTE_ADDR'] = '';
		$_SERVER['HTTP_HOST'] = 'dev.pfm.my';
		self::assertTrue(Application::is_dev());
	}

    /**
     * @covers common\classes\Application::init_validator
     */
    public function test_init_validator() {
        Application::init_validator();
    }
}