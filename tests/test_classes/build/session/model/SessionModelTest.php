<?php
require_once ROOT_PATH.DS."build".DS.'session'.DS.'model'.DS.'sessionmodel.php';

class SessionModelTest extends \PHPUnit_Framework_TestCase {
	private $model;

	public function setUp() {
		$system = Application::get_class('System');
		$params = $system->get_configuration()['db_params'];
		$this->model = Application::get_class('SessionModel', $params);
	}

	/**
	 * @covers SessionModel::start_session
	 */
	public function test_start_session() {
		$this->assertInternalType('int', $this->model->start_session());
	}
}
 