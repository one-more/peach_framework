<?php

class SuperModelTest extends PHPUnit_Framework_TestCase {
	private $model;

	public function setUp() {
		if(is_null($this->model)) {
			$system = Application::get_class('System');
			$params = $system->get_configuration()['db_params'];
			$this->model  = Application::get_class('SuperModel', $params);
		}
	}

	/**
	 * @covers SuperModel::execute
	 */
	public function test_execute() {
		$sql = 'create table if not exists tests_table (';
		$sql .= ' `id` serial primary key';
		$sql .= ', `field1` varchar(255) not null default ""';
		$sql .= ', `field2` bigint not null default 0';
		$sql .= ', `field3` enum("val1", "val2", "val3") default "val1"';
		$sql .= ')';
		$this->assertNull($this->model->execute($sql));
	}

	/**
	 * @covers SuperModel::select
	 */
	public function test_select() {
		$params = [
			'where' => 'id = 1'
		];
		$this->assertCount(4, $this->model->select('tests_table', $params));

		$params = [
			'fields' => ['id', 'field1'],
			'where' => 'id = 1'
		];
		$this->assertCount(2, $this->model->select('tests_table', $params));

		$params = [
			'limit' => 2,
			'offset' => 1
		];
		$this->assertCount(2, $this->model->select('tests_table', $params));
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
	 * @covers SuperModel::insert
	 * @dataProvider values_provider
	 */
	public function test_insert($fields) {
		$params = ['fields' => $fields];
		$this->assertInternalType('int', $this->model->insert('tests_table', $params));
	}

	/**
	 * @covers SuperModel::insert
	 * @expectedException InvalidArgumentException
	 */
	public function test_empty_insert() {
		$this->assertInternalType('int', $this->model->insert('tests_table'));
	}

	/**
	 * @covers SuperModel::update
	 * @dataProvider values_provider
	 */
	public function test_update($fields) {
		if(empty($update_id)) {
			static $update_id = 0;
		}
		$params = [
			'fields' => $fields,
			'where' => 'id = '.((++$update_id))
		];
		$this->assertNull($this->model->update('tests_table', $params));
	}

	/**
	 * @covers SuperModel::update
	 * @expectedException InvalidArgumentException
	 */
	public function test_empty_update() {
		$this->assertInternalType('int', $this->model->update('tests_table'));
	}

	/**
	 *  @covers SuperModel::delete
	 */
	public function test_delete() {
		$max_id = (int)$this->model->execute('select max(id) from tests_table');
		$this->assertInternalType('int', $max_id);

		$this->assertNull($this->model->delete('tests_table', "id = {$max_id}"));
	}

	/**
	 * @covers SuperModel::get_arrays
	 */
	public function test_get_arrays() {
		$this->assertCount(0, $this->model->get_arrays('tests_table', ['where' => 'id = 0']));

		$this->assertCount(1, $this->model->get_arrays('tests_table', ['where' => 'id = 1']));

		$this->assertCount(3, $this->model->get_arrays('tests_table', ['where' => 'id in(1,2,3)']));
	}

	/**
	 * @covers SuperModel::get_array
	 */
	public function test_get_array() {
		$this->assertCount(0, $this->model->get_array('tests_table', ['where' => 'id = 0']));

		$this->assertCount(4, $this->model->get_array('tests_table', ['where' => 'id = 1']));

		$this->assertCount(4, $this->model->get_array('tests_table', ['where' => 'id in(1,2,3)']));

		$params = [
			'fields' => ['id', 'field1'],
			'where' => 'id = 2'
		];
		$this->assertCount(2, $this->model->get_array('tests_table', $params));
	}

	/**
	 * @covers SuperModel::execute
	 */
	public function test_execute_results() {
		$this->assertEquals(1, $this->model->execute('select min(id) from tests_table'));

		$this->assertEquals([1,2,3], $this->model->execute('select id from tests_table where id in(1,2,3)'));

		$record = $this->model->execute('select * from tests_table where id = 1');
		foreach(['id', 'field1', 'field2', 'field3'] as $key) {
			$this->assertArrayHasKey($key, $record);
		}

		$this->assertCount(3, $this->model->execute('select * from tests_table where id in(1,2,3)'));
	}
}
 