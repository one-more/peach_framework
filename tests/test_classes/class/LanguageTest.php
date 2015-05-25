<?php

class languageTest extends PHPUnit_Framework_TestCase {
	private $default_language;
	private $lang_obj;

	public function __construct() {
		$system = Application::get_class('System');
		$this->default_language = $system->get_configuration()['language'];
		$this->lang_obj = Application::get_class('Language');
	}

	/**
	 * @covers Language::get_language
	 */
	public function test_get_language() {
		$this->assertEquals($this->default_language, $this->lang_obj->get_language());
		$_COOKIE['language'] = 'EN';
		$this->assertEquals('EN', $this->lang_obj->get_language());
		unset($_COOKIE['language']);
	}

	/**
	 * @covers Language::set_language
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function test_set_language() {
		$this->assertNull($this->lang_obj->set_language('EN'));
		$this->assertEquals('EN', $this->lang_obj->get_language());
		$this->assertNull($this->lang_obj->set_language($this->default_language));
		$_COOKIE['language'] = 'EN';
		$this->assertNull($this->lang_obj->set_language($this->default_language));
		$this->assertEquals($this->default_language, $this->lang_obj->get_language());
	}
}