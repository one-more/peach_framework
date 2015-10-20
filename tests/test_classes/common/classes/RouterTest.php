<?php

use common\classes\Router;
use common\classes\Application;

class FakeRouter extends Router {

	public function __construct() {

		$this->routes = [
			'/users' => [$this, 'users'],
			'/user/:number' => ['FakeRouter', 'user'],
			'/team/:string' => ['FakeRouter', 'team', 'no check'],
			'/*not_found' => [$this, 'not_found']
		];
	}

	public function get_routes() {
		return $this->routes;
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
    
    /**
     * @var $router FakeRouter
     */
    private $router;

	public function setUp() {
		if(is_null($this->router)) {
			$this->router = Application::get_class('FakeRouter');
		}
	}

	/**
	 * @covers Router::set_routes
	 */
	public function test_set_routes() {
		$router_routes = $this->router->get_routes();
		$test_routes = [
			'/test' => [$this->router, 'test']
		];
		$this->router->set_routes($test_routes);
		self::assertEquals($test_routes, $this->router->get_routes());
		$this->router->set_routes($router_routes);
	}

	/**
	 * @covers Router::route
	 */
	public function test_route() {
		$_SERVER['REQUEST_URI'] = '/users';
		self::assertEquals($this->router->route(), $this->router->users());

		$_SERVER['REQUEST_URI'] = '/user/1';
		self::assertEquals($this->router->route(), $this->router->user(1));

		$_SERVER['REQUEST_URI'] = '/team/dynamo';
		self::assertEquals($this->router->route(), FakeRouter::team('dynamo'));

		$_SERVER['REQUEST_URI'] = '/not_existed_page';
		self::assertEquals($this->router->route(), $this->router->not_found());

		$router_routes = $this->router->get_routes();
		$this->router->set_routes([]);
		self::assertNull($this->router->route());
		$this->router->set_routes($router_routes);
	}

	/**
	 * @covers Router::get_callback
	 */
	public function test_get_callback() {
		$method = new ReflectionMethod($this->router, 'get_callback');
		$method->setAccessible(true);

		$_SERVER['REQUEST_URI'] = '/users';
		$callback = $method->invoke($this->router);
		self::assertEquals($callback, [$this->router, 'users']);

		$_SERVER['REQUEST_URI'] = '/user/1';
		$callback = $method->invoke($this->router);
		self::assertEquals($callback, ['FakeRouter', 'user']);

		$_SERVER['REQUEST_URI'] = '/team/dynamo';
		$callback = $method->invoke($this->router);
		self::assertEquals($callback, ['FakeRouter', 'team', 'no check']);

		$_SERVER['REQUEST_URI'] = '/not_existed_page';
		$callback = $method->invoke($this->router);
		self::assertEquals($callback, [$this->router, 'not_found']);

		$this->router->set_routes([]);
		self::assertFalse($method->invoke($this->router));
	}
}
 