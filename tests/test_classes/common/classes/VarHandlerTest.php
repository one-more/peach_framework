<?php

use common\classes\VarHandler;

class VarHandlerTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers common\classes\VarHandler::clean_html
	 */
	public function test_clean_html() {
		$str = '<a href="#">click me</a>';
		$clear_str = 'click me';
		self::assertEquals($clear_str, VarHandler::clean_html($str));
		$str = '<div><a href="#">click me</a></div>';
		$clear_str = '<a href="#">click me</a>';
		self::assertEquals($clear_str, VarHandler::clean_html($str, '<a>'));
	}

	public function validate_var_provider() {
		return [
			[1, 'raw', true],
			[1.5, 'raw', true],
			['', 'raw', true],
			['asd', 'raw', true],
			['asd@asd', 'raw', true],
			['asd.ds', 'raw', true],
			[[], 'raw', true],
			[new StdClass, 'raw', true],
			[null, 'raw', true],
			[true, 'raw', true],
			[false, 'raw', true],

			[1, 'int', true],
			[0, 'int', false],
			[[], 'int', false],
			[false, 'int', false],
			['', 'int', false],
			['asd', 'int', false],
			[null, 'int', false],
			[new StdClass, 'int', false],
			[true, 'int', true],

			[1, null, true],

			[1, 'not_existed_filter', false],

			[1, 'boolean', true],
			[[1], 'boolean', true],
			[new StdClass, 'boolean', true],
			['1', 'boolean', true],
			[true, 'boolean', true],
			[null, 'boolean', false],
			[0, 'boolean', false],
			[[], 'boolean', false],
			['', 'boolean', false],
			[false, 'boolean', false],

			['asd', 'email', false],
			['', 'email', false],
			[1, 'email', false],
			[true, 'email', false],
			[false, 'email', false],
			[[], 'email', false],
			[new StdClass, 'email', false],
			['google.com', 'email', false],
			[null, 'email', false],
			['asd@asd.ds', 'email', true],

			[1, 'float', true],
			[null, 'float', false],
			['', 'float', false],
			[[], 'float', false],
			[new StdClass, 'float', false],
			[true, 'float', true],
			[false, 'float', false],
			[0, 'float', false],
			[1.5, 'float', true],

			['asd', 'special_chars', true],

			['asd', 'string', true],
			[null, 'string', false],
			[1, 'string', false],
			[1.5, 'string', false],
			[[], 'string', false],
			[true, 'string', false],
			[false, 'string', false],
			[new StdClass, 'string', false],
			['', 'string', true],

			['', 'url', false],
			[true, 'url', false],
			[false, 'url', false],
			[[], 'url', false],
			[new StdClass, 'url', false],
			[null, 'url', false],
			[1, 'url', false],
			[1.5, 'url', false],
			['asddddddddd', 'url', false],
			['asd@asd.asd.asd', 'url', false],
			['asd.ds', 'url', true],
			['www.asd.ds', 'url', true],
			['www.asd.ds.ds', 'url', true],
			['asd.ds?asd=f', 'url', true],
			['http://www.asd.ds', 'url', true],
			['http://www.asd.ds/', 'url', true],
			['http://www.asd.ds/#ds', 'url', true],
			['http://www.asd.ds/#ds??', 'url', false]
		];
	}

	/**
	 * @param string $var
	 * @param string $filter
	 * @param string $expected
	 * @covers common\classes\VarHandler::validate_var
	 * @dataProvider validate_var_provider
	 */
	public function test_validate_var($var, $filter, $expected) {
		self::assertEquals($expected, VarHandler::validate_var($var, $filter));
	}

	public function sanitize_var_provider() {
		return [
			['', 'raw', '', ''],
			['', 'raw', null, null],
			['', 'raw', 'asd', 'asd'],
			[1, 'raw', '', 1],
			[1, 'raw', '2', 1],
			[[], 'raw', '2', '2'],
			[true, 'raw', '2', true],
			[false, 'raw', '2', '2'],
			[null, 'raw', '2', '2'],
			[null, 'raw', null, null],
			[new StdClass, 'raw', null, null],
			[[1], 'raw', '2', [1]],

			[1, 'int', '', 1],
			[true, 'int', '', true],
			[false, 'int', '', false],
			[[], 'int', '', ''],
			[null, 'int', '', ''],
			['', 'int', '', ''],
			['asd', 'int', '', ''],
			['asd@asd', 'int', '', ''],
			[new StdClass, 'int', '', ''],
			[0, 'int', '', ''],

			[1, 'email', '', '1'],
			['asd', 'email', '', 'asd'],
			[false, 'email', '', ''],
			[null, 'email', '', ''],
			[[], 'email', '', ''],
			['', 'email', '', ''],
			[new StdClass, 'email', '', ''],
			['asd@asd', 'email', '', 'asd@asd'],
			['asd@asd.ds', 'email', '', 'asd@asd.ds'],
			['<asd@asd.ds', 'email', '', 'asd@asd.ds'],
			[true, 'email', '', '1'],

			[1, 'float', '', 1],
			[.5, 'float', '', 0.5],
			['1', 'float', '', 1],
			[['1'], 'float', '', ''],
			[true, 'float', '', 1],
			[false, 'float', '', ''],
			[null, 'float', '', ''],
			['asd', 'float', '', ''],
			[new StdClass, 'float', '', ''],
			[1.5, 'float', '', 1.5],

			['asd', 'special_chars', '', 'asd'],
			[1, 'special_chars', '', '1'],
			[[], 'special_chars', '', ''],
			[new StdClass, 'special_chars', '', ''],
			[1.5, 'special_chars', '', '1.5'],
			[true, 'special_chars', '', '1'],
			[false, 'special_chars', '', ''],
			[null, 'special_chars', '', null],
			['<a href="#">sd&nbsp;&copy;</a>', 'special_chars', '', htmlspecialchars('<a href="#">sd&nbsp;&copy;</a>', ENT_QUOTES)],

			[1, 'string', '', '1'],
			[true, 'string', '', '1'],
			[[], 'string', '', ''],
			['', 'string', '', ''],
			['asd', 'string', '', 'asd'],
			['<a>asd</a>', 'string', '', 'asd'],
			[null, 'string', '', ''],
			[New StdClass, 'string', '', ''],
			[1.5, 'string', '', '1.5'],
			['asd#!:&nbsp;', 'string', '', 'asd#!:&nbsp;'],
			[false, 'string', '', ''],

			[1, 'url', '', '1'],
			[true, 'url', '', '1'],
			[false, 'url', '', ''],
			[[], 'url', '', ''],
			[new StdClass, 'url', '', ''],
			[null, 'url', '', ''],
			['asd', 'url', '', 'asd'],
			['google.com', 'url', '', 'google.com'],
			[1.5, 'url', '', '1.5']
		];
	}

	/**
	 * @param string $var
	 * @param string $filter
	 * @param string $default
	 * @param string $expected
	 * @covers common\classes\VarHandler::sanitize_var
	 * @dataProvider sanitize_var_provider
	 */
	public function test_sanitize_var($var, $filter, $default, $expected) {
		self::assertEquals($expected, VarHandler::sanitize_var($var, $filter, $default));
	}
}
 