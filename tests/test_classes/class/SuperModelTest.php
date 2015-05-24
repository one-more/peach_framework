<?php

class SuperModelTest extends PHPUnit_Framework_TestCase {
	private $model;

	public function __construct() {
		$system = Application::get_class('System');
        $params = $system->get_configuration()['db_params'];
        $this->model  = Application::get_class('SuperModel', $params);
	}

	/**
	 * @covers SuperModel::execute
	 */
	public function test_execute() {
		$sql = 'create table if not exists tests_table (';
		$sql .= ' `id` serial primary key';
		$sql .= ')';
		$this->assertNull($this->model->execute($sql));
	}
}
 