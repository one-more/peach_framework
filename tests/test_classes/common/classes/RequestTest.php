<?php

use common\classes\Request;

class RequestTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers common\classes\Request::is_ajax
	 */
	public function test_is_ajax() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
		self::assertTrue(Request::is_ajax());
	}

    /**
     * @covers common\classes\Request::is_post
     */
	public function test_is_post() {
        $_SERVER['REQUEST_METHOD'] = 'post';
        self::assertTrue(Request::is_post());
    }

    /**
     * @covers common\classes\Request::is_get
     */
    public function test_is_get() {
        $_SERVER['REQUEST_METHOD'] = 'get';
        self::assertTrue(Request::is_get());
    }

	public function get_var_provider() {
		$_REQUEST['test_int'] = 1;
		$_REQUEST['test_float'] = 1.3;
		$_REQUEST['test_email'] = 'test@test.test';
		$special_chars_string = '<a href="#">click me</a>&nbsp;<br>';
		$special_chars_string_handled = htmlspecialchars($special_chars_string, ENT_QUOTES);
		$_REQUEST['test_special_chars'] = $special_chars_string;
		$_REQUEST['test_full_url'] = $full_url ='http://test.com';
		$_REQUEST['test_short_url'] = $short_url = 'test.com';
		$_REQUEST['test_search_params_url'] = $search_params_url = 'google.com?asd=bfg&p=1';

		return [
			['test_int', 'int', null, 1],
			['test_int', null, null, 1],
			['test_int', 'string', null, null],
			['test_int', 'special_chars', null, 1],
			['test_int', 'url', null, null],
			['test_int', 'not_existed_filter', null, null],
			['test_int', 'email', null, null],

			['test_email', 'email', null, 'test@test.test'],
			['test_email', 'int', null, null],
			['test_email', 'string', null, 'test@test.test'],
			['test_email', 'special_chars', null, 'test@test.test'],
			['test_email', 'url', null, null],
			['test_email', 'float', null, null],

			['test_float', 'int', null, 1],
			['test_float', 'float', null, 1.3],
			['test_float', 'string', null, null],
			['test_float', 'special_chars', null, 1.3],
			['test_float', 'url', null, null],
			['test_float', 'email', null, null],

			['test_special_chars', 'special_chars', null, $special_chars_string_handled],
			['test_special_chars', 'int', null, null],
			['test_special_chars', 'email', null, null],
			['test_special_chars', 'url', null, null],
			['test_special_chars', null, null, $special_chars_string],
			['test_special_chars', 'string', null, strip_tags($special_chars_string)],
			['test_special_chars', 'float', null, null],

			['test_full_url', 'url', null, $full_url],
			['test_full_url', 'string', null, $full_url],
			['test_full_url', 'int', null, null],
			['test_full_url', 'float', null, null],
			['test_full_url', 'email', null, null],
			['test_short_url', 'url', null, $short_url],
			['test_short_url', 'special_chars', null, htmlspecialchars($short_url, ENT_QUOTES)],
			['test_search_params_url', 'url', null, $search_params_url],

			['test_not_existed_var', null, null, null]
		];
	}

	/**
	 * @param string $name
	 * @param string $filter
	 * @param string $default
	 * @param string $expected
	 * @covers common\classes\Request::get_var
	 * @dataProvider get_var_provider
	 */
	public function test_get_var($name, $filter, $default, $expected) {
		self::assertEquals($expected, Request::get_var($name, $filter, $default));
	}

	/**
	 * @covers common\classes\Request::uri
	 */
	public function test_uri() {
		self::assertEquals('', Request::uri());
		$_SERVER['REQUEST_URI'] = '/test.com?asd=dsa';
		self::assertEquals('/test.com', Request::uri());

		$_SERVER['REQUEST_URI'] = '/test.com/';
		self::assertEquals('/test.com', Request::uri());
	}

	/**
	 * @covers common\classes\Request::uri_parts
	 */
	public function test_uri_parts() {
		$_SERVER['REQUEST_URI'] = '/test.com/test1?asd=dsa';
		self::assertEquals(['test.com', 'test1'], Request::uri_parts());
	}

	/**
	 * @covers common\classes\Request::search_params
	 */
	public function test_search_params() {
		$_SERVER['REQUEST_URI'] = '/test.com/test1';
		self::assertEquals([], Request::search_params());
		$_SERVER['REQUEST_URI'] = '/test.com/test1?asd=dsa';
		self::assertEquals(['asd'=>'dsa'], Request::search_params());
	}

    /**
     * common\classes\Request::is_token_valid
     */
    public function test_is_token_valid() {
        $_GET['token'] = md5(uniqid('token', true));
        self::assertFalse(Request::is_token_valid());
    }
}
 