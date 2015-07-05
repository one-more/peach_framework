<?php

class MysqlModelTestImpl extends MysqlModel {

	protected function get_table() {
		return 'tests_table2';
	}
}

class MysqlModelTest extends PHPUnit_Framework_TestCase {

	private $model;

	public function setUp() {
		if(is_null($this->model)) {
			$this->model = new MysqlModelTestImpl();
		}
	}

	/**
	 * @covers MysqlModel::__construct
	 */
	public function test_create_model() {
		$this->assertInternalType('object', new MysqlModelTestImpl);
	}

	/**
	 * @covers MysqlModel::add_lang
	 */
	public function test_add_lang() {
		/**
		 * @var $language Language
		 */
		$language = Application::get_class('Language');
		$get_table_method = new ReflectionMethod($this->model, 'get_table');
		$get_table_method->setAccessible(true);
		$add_lang_method = new ReflectionMethod($this->model, 'add_lang');
		$add_lang_method->setAccessible(true);
		$this->assertEquals(
			$add_lang_method->invoke($this->model, $get_table_method->invoke($this->model)),
			$language->get_language().'_'.$get_table_method->invoke($this->model)
		);
	}

	public function values_provider() {
		$result = [];
		for($i=0; $i<3; $i++) {
			$result[][] = [
				'field1' => md5(rand()),
				'field2' => mt_rand(),
				'field3' => ['val1', 'val2', 'val3'][rand(0,2)]
			];
		}
		return $result;
	}

	/**
	 * @param $fields
	 * @covers MysqlModel::insert
	 * @dataProvider values_provider
	 */
	public function test_insert($fields) {
		$insert = new ReflectionMethod($this->model, 'insert');
		$insert->setAccessible(true);
		$execute = new ReflectionMethod($this->model, 'execute');
		$execute->setAccessible(true);

		$insert->invoke($this->model, $fields);
		$execute->invoke($this->model);
	}

	/**
	 * @covers MysqlModel::update
	 * @dataProvider values_provider
	 */
	public function test_update($fields) {
		static $update_id = 1;
		$update = new ReflectionMethod($this->model, 'update');
		$update->setAccessible(true);
		$where = new ReflectionMethod($this->model, 'where');
		$where->setAccessible(true);
		$execute = new ReflectionMethod($this->model, 'execute');
		$execute->setAccessible(true);

		$update->invoke($this->model, $fields);
		$where->invoke($this->model, [
			'id' => ['=', $update_id++, false]
		]);
		$execute->invoke($this->model);
	}
} 