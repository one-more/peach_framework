<?php

use common\classes\Application;
use common\classes\Language;

class languageTest extends PHPUnit_Framework_TestCase {
	private $default_language;

    /**
     * @var $lang_obj Language
     */
    private $lang_obj;

	public function setUp() {
		$system = Application::get_class(System::class);
		$this->default_language = $system->get_configuration()['language'];
		$this->lang_obj = Application::get_class(Language::class);
	}

	/**
	 * @covers common\classes\Language::get_language
	 */
	public function test_get_language() {
		self::assertEquals($this->default_language, $this->lang_obj->get_language());
		$_COOKIE['language'] = 'EN';
		self::assertEquals('EN', $this->lang_obj->get_language());
		unset($_COOKIE['language']);
	}

	/**
	 * @covers common\classes\Language::set_language
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function test_set_language() {
		$this->lang_obj->set_language('EN');
		self::assertEquals('EN', $this->lang_obj->get_language());
		$this->lang_obj->set_language($this->default_language);
		$_COOKIE['language'] = 'EN';
		$this->lang_obj->set_language($this->default_language);
		self::assertEquals($this->default_language, $this->lang_obj->get_language());
	}
}