<?php
require_once ROOT_PATH.DS.'build'.DS.'session'.DS.'model'.DS.'sessionmodel.php';

/**
 * Class SessionModelTest
 *
 * @method bool assertInternalType($a,$b)
 * @method bool assertCount($a,$b)
 * @method bool assertEquals($a,$b)
 * @method bool assertNull($var)
 * @method bool assertFalse($var)
 */
class SessionModelTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var $model Session\model\SessionModel
     */
    private $model;

	private static $session_id;

	public function setUp() {
		if(empty($this->model)) {
			$this->model = Application::get_class('Session\model\SessionModel');
		}
		if(!empty(static::$session_id)) {
			$_COOKIE['pfm_session_id'] = static::$session_id;
		}
	}

    /**
     * @covers Session\model\SessionModel::get_table
     */
	public function test_get_table() {
        $method = new ReflectionMethod($this->model, 'get_table');
        $method->setAccessible(true);
        $this->assertEquals('session', $method->invoke($this->model));
    }

	/**
	 * @covers Session\model\SessionModel::start_session
	 */
	public function test_start_session() {
		$session_id = $this->model->start_session();
		$this->assertInternalType('int', $session_id);
		static::$session_id = $session_id;
	}

	/**
	 * @covers Session\model\SessionModel::get_vars
	 */
	public function test_get_vars() {
		$method = new ReflectionMethod($this->model, 'get_vars');
		$method->setAccessible(true);
		$this->assertCount(0, $method->invoke($this->model));
	}

	/**
	 * @covers Session\model\SessionModel::set_var
	 */
	public function test_set_var() {
        $this->model->set_var('test', 'test');
	}

	/**
	 * @covers Session\model\SessionModel::get_var
	 */
	public function test_get_var() {
		$this->assertEquals('test', $this->model->get_var('test'));
	}

	/**
	 * @covers Session\model\SessionModel::unset_var
	 */
	public function test_unset_var() {
        $this->model->unset_var('test');
		$this->assertFalse($this->model->get_var('test', false));
	}

	/**
	 * @covers Session\model\SessionModel::set_uid
	 */
	public function test_set_uid() {
        $this->model->set_uid(1);
	}

	/**
	 * @covers Session\model\SessionModel::get_uid
	 */
	public function test_get_uid() {
		$this->assertEquals(1, $this->model->get_uid());
	}
}
 