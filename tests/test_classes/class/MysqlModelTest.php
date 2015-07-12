<?php

class MysqlModelTestImpl extends MysqlModel {
	public $table_name = 'tests_table2';

	protected function get_table() {
		return $this->table_name;
	}

	public function add_lang($table_name) {
		return parent::add_lang($table_name);
	}

	public function select($fields = null) {
		parent::select($fields);
		return $this;
	}

	public function update($fields) {
		parent::update($fields);
		return $this;
	}

	public function insert($fields) {
		parent::insert($fields);
		return $this;
	}

	public function delete() {
		parent::delete();
		return $this;
	}

	public function where($conditions) {
		parent::where($conditions);
		return $this;
	}

	public function parse_conditions($conditions) {
		parent::parse_conditions($conditions);
	}

	public function having($conditions) {
		parent::having($conditions);
		return $this;
	}

	public function limit($num) {
		parent::limit($num);
		return $this;
	}

	public function offset($num) {
		parent::offset($num);
		return $this;
	}

	public function execute($sql = null) {
		parent::execute($sql);
		return $this;
	}

	public function order_by($fields) {
		parent::order_by($fields);
		return $this;
	}

	public function get_result() {
		return parent::get_result();
	}

	public function get_query() {
		return $this->query;
	}

	public function sub_query() {
		return parent::sub_query();
	}
}

class MysqlModelTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var $model MysqlModelTestImpl
	 */
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
		$this->assertEquals(
			$this->model->add_lang($this->model->table_name),
			$language->get_language().'_'.$this->model->table_name
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
		$this->model->insert($fields)
			->execute();
	}

	/**
	 * @covers MysqlModel::update
	 * @dataProvider values_provider
	 */
	public function test_update($fields) {
		static $update_id = 1;
		$this->model->update($fields)
			->where(['id'=> ['=', $update_id++, false]])
			->execute();
	}

	/**
	 * @covers MysqlModel::select
	 */
	public function test_select() {
		$id = $this->model->select(['id'])
			->where(['id' => ['=', 1]])
			->execute()
			->get_result();
		$this->assertEquals(1, $id);
	}

	/**
	 * @covers MysqlModel::delete
	 */
	public function test_delete() {
		$this->model->delete()
			->order_by(['id desc'])
			->limit(1)
			->execute();
	}

	/**
	 * @covers MysqlModel::where
	 */
	public function test_where() {
		$id = $this->model->select(['id'])
			->where(['id' => ['>', 1]])
			->limit(1)
			->execute()
			->get_result();
		$this->assertInternalType('int', (int)$id);
		$row = $this->model->select()
			->where('id > 1')
			->limit(1)
			->execute()
			->get_result();
		$this->assertINternalType('array', $row);
	}

	/**
	 * @covers MysqlModel::parse_conditions
	 */
	public function test_parse_conditions() {
		$this->model->parse_conditions([
			'id' => ['=', 1],
			'or' => ['id' => ['=', 2]],
			'and' => ['id' => ['<', 3, false]]
		]);
		$this->assertEquals('id = ? or id = ? and id < 3', trim($this->model->get_query()));
	}
} 