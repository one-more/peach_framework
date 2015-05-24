<?php

class FakeRouter extends Router {

	public function __construct() {
		$this->routes = [
			'/users' => [$this, 'users'],
			'/user/:number' => ['FakeRouter', 'user'],
			'/team/:string' => ['FakeRouter', 'team'],
			'/*not_found' => [$this, 'not_found']
		];
	}

	public function users() {
		return 'users page';
	}

	public function user($id) {
		return "user with id = {$id}";
	}

	public static function team($name) {
		return "team {$name}";
	}

	public function not_found() {
		return 'not existed page';
	}
}

class RouterTest extends PHPUnit_Framework_TestCase {
	private $router;

	public function __construct() {
		$this->router = Application::get_class('FakeRouter');
	}

	/**
	 * @covers Router::get_callback
	 */
	public function test_get_callback() {
		$method = new ReflectionMethod($this->router, 'get_callback');
		$method->setAccessible(true);

		$_SERVER['REQUEST_URI'] = '/users';
		$callback = $method->invoke($this->router);
		$this->assertEquals($callback, [$this->router, 'users']);

		$_SERVER['REQUEST_URI'] = '/user/1';
		$callback = $method->invoke($this->router);
		$this->assertEquals($callback, ['FakeRouter', 'user']);

		$_SERVER['REQUEST_URI'] = '/team/dynamo';
		$callback = $method->invoke($this->router);
		$this->assertEquals($callback, ['FakeRouter', 'team']);

		$_SERVER['REQUEST_URI'] = '/not_existed_page';
		$callback = $method->invoke($this->router);
		$this->assertEquals($callback, [$this->router, 'not_found']);
	}

	/**
	 * @covers Router::route
	 */
	public function test_route() {
		$_SERVER['REQUEST_URI'] = '/users';
		$this->assertEquals($this->router->route(), $this->router->users());

		$_SERVER['REQUEST_URI'] = '/user/1';
		$this->assertEquals($this->router->route(), $this->router->user(1));

		$_SERVER['REQUEST_URI'] = '/team/dynamo';
		$this->assertEquals($this->router->route(), FakeRouter::team('dynamo'));

		$_SERVER['REQUEST_URI'] = '/not_existed_page';
		$this->assertEquals($this->router->route(), $this->router->not_found());
	}
}
 