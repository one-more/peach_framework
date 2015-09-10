<?php
require_once ROOT_PATH.DS.'build'.DS.'session'.DS.'model'.DS.'sessionmodel.php';

class SessionModelTest extends \PHPUnit_Framework_TestCase {
	private $model;
	private static $session_id;

	public function setUp() {
		if(empty($this->model)) {
			$system = Application::get_class('System');
			$params = $system->get_configuration()['db_params'];
			$this->model = Application::get_class('SessionModel', $params);
		}
		if(!empty(static::$session_id)) {
			$_COOKIE['pfm_session_id'] = static::$session_id;
		}
	}

	/**
	 * @covers SessionModel::start_session
	 */
	public function test_start_session() {
		$session_id = $this->model->start_session();
		$this->assertInternalType('int', $session_id);
		static::$session_id = $session_id;
	}

	/**
	 * @covers SessionModel::get_vars
	 */
	public function test_get_vars() {
		$method = new ReflectionMethod($this->model, 'get_vars');
		$method->setAccessible(true);
		$this->assertCount(0, $method->invoke($this->model));
	}

	/**
	 * @covers SessionModel::set_var
	 */
	public function test_set_var() {
		$this->assertNull($this->model->set_var('test', 'test'));
	}

	/**
	 * @covers SessionModel::get_var
	 */
	public function test_get_var() {
		$this->assertEquals('test', $this->model->get_var('test'));
	}

	/**
	 * @covers SessionModel::unset_var
	 */
	public function test_unset_var() {
		$this->assertNull($this->model->unset_var('test'));
		$this->assertFalse($this->model->get_var('test', false));
	}

	/**
	 * @covers SessionModel::set_uid
	 */
	public function test_set_uid() {
		$this->assertNull($this->model->set_uid(1));
	}

	/**
	 * @covers SessionModel::get_uid
	 */
	public function test_get_uid() {
		$this->assertEquals(1, $this->model->get_uid());
	}
}
 