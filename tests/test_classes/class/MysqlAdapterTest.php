<?php

class MysqlAdapterTestImpl extends MysqlAdapter {
    public $table_name;
	
    public function __construct() {
		parent::__construct('tests_table2');
	}

	public function get_configuration() {
		return parent::get_configuration();
	}

	public function add_lang($table_name) {
		return parent::add_lang($table_name);
	}

	public function select(array $fields = null) {
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

	public function group_by($fields) {
		parent::group_by($fields);
		return $this;
	}

	public function join($type, $table, $conditions) {
		parent::join($type, $table, $conditions);
		return $this;
	}

	public function get_result() {
		return parent::get_result();
	}

	public function get_query() {
		return $this->query;
	}

	public function get_statement() {
		return $this->statement;
	}

	public function get_array() {
		return parent::get_array();
	}

	public function get_arrays() {
		return parent::get_arrays();
	}

	public function get_insert_id() {
		return parent::get_insert_id();
	}

	public function get_arrays_from_statement(PDOStatement $sth) {
		$method = new ReflectionMethod('MysqlAdapter', 'get_arrays_from_statement');
		$method->setAccessible(true);
        return $method->invoke($this, $sth);
	}

	public function data_to_arrays($data) {
        $method = new ReflectionMethod('MysqlAdapter', 'data_to_arrays');
        $method->setAccessible(true);
        return $method->invoke($this, $data);
	}

	public function get_array_from_statement(PDOStatement $sth) {
        $method = new ReflectionMethod('MysqlAdapter', 'get_array_from_statement');
        $method->setAccessible(true);
        return $method->invoke($this, $sth);
	}

	public function data_to_array($data) {
        $method = new ReflectionMethod('MysqlAdapter', 'data_to_array');
        $method->setAccessible(true);
        return $method->invoke($this, $data);
	}

	public function return_from_statement(PDOStatement $sth) {
        $method = new ReflectionMethod('MysqlAdapter', 'return_from_statement');
        $method->setAccessible(true);
        return $method->invoke($this, $sth);
	}
}

/**
 * Class MysqlAdapterTest
 *
 * @method bool assertInternalType($a, $b)
 * @method bool assertEquals($a, $b)
 * @method bool assertTrue($var)
 * @method bool assertFalse($var)
 */
class MysqlAdapterTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var $model MysqlAdapterTestImpl
	 */
	private $model;

	public function setUp() {
		$this->model = new MysqlAdapterTestImpl();
	}

	/**
	 * @covers MysqlAdapter::__construct
	 */
	public function test_create_model() {
		$this->assertInternalType('object', new MysqlAdapterTestImpl());
	}

	/**
	 * @covers MysqlAdapter::__construct
	 * @expectedException InvalidDBParamException
	 */
	public function test_create_model_no_use_db() {
		/**
		 * @var $system System
		 */
		$system = Application::get_class('System');
		$use_db = $system->get_use_db_param();
		$system->set_use_db_param(false);
		try {
			$this->assertInternalType('object', new MysqlAdapterTestImpl);
		} catch(InvalidDBParamException $e) {
			$system->set_use_db_param($use_db);
			throw $e;
		}
	}

	/**
	 * @covers MysqlAdapter::add_lang
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

	/**
	 * @covers MysqlAdapter::get_configuration
	 */
	public function test_get_configuration() {
		$this->assertTrue(ArrayHelper::is_assoc_array($this->model->get_configuration()));
	}

	public function values_provider() {
		$result = [];
		for($i=0; $i<3; $i++) {
			$result[][] = [
				'field1' => md5(mt_rand()),
				'field2' => mt_rand(),
				'field3' => ['val1', 'val2', 'val3'][mt_rand(0,2)]
			];
		}
		return $result;
	}

	/**
	 * @param $fields
	 * @covers MysqlAdapter::insert
	 * @dataProvider values_provider
	 */
	public function test_insert($fields) {
		$this->model->insert($fields);
		$this->model->execute();

		$this->model->table_name = 'tests_table';
        $this->model->insert($fields);
		$this->model->execute();
	}

	/**
	 * @covers MysqlAdapter::insert
	 * @expectedException InvalidArgumentException
	 */
	public function test_insert_no_fields() {
		$this->model->insert(null)
			->execute();
	}

	/**
     * @param array $fields
	 * @covers MysqlAdapter::update
	 * @dataProvider values_provider
	 */
	public function test_update($fields) {
		static $update_id = 1;
		$this->model->update($fields)
			->where(['id'=> ['=', $update_id++, false]])
			->execute();
	}

	/**
	 * @covers MysqlAdapter::update
	 * @expectedException InvalidArgumentException
	 */
	public function test_update_no_fields() {
		$this->model->update(null)
			->where(['id'=> ['=', 1, false]])
			->execute();
	}

	/**
	 * @covers MysqlAdapter::select
	 */
	public function test_select() {
		$id = $this->model->select(['id'])
			->where(['id' => ['=', 1]])
			->execute()
			->get_result();
		$this->assertEquals(1, $id);

		$result = $this->model->select()
			->limit(1)
			->execute()
			->get_result();
		$this->assertCount(4, $result);
	}

	/**
	 * @covers MysqlAdapter::delete
	 */
	public function test_delete() {
		$this->model->delete()
			->order_by(['id desc'])
			->limit(1)
			->execute();
	}

	/**
	 * @covers MysqlAdapter::where
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
	 * @covers MysqlAdapter::limit
	 */
	public function test_limit() {
		$result = $this->model->select()
			->limit(1)
			->execute()
			->get_result();
		$this->assertCount(4, $result);
	}

	/**
	 * @covers MysqlAdapter::offset
	 */
	public function test_offset() {
		$result = $this->model->select()
			->limit(1)
			->offset(1)
			->execute()
			->get_result();
		$this->assertCount(4, $result);
	}

	/**
	 * @covers MysqlAdapter::parse_conditions
	 */
	public function test_parse_conditions() {
		$this->model->parse_conditions([
			'id' => ['=', 1],
			'or' => ['id' => ['=', 2]],
			'and' => ['id' => ['<', 3, false]]
		]);
		$this->assertEquals('id = ? or id = ? and id < 3', trim($this->model->get_query()));
	}

	/**
	 * @covers MysqlAdapter::join
	 */
	public function test_join() {
		$result = $this->model->select()
			->join('INNER', 'tests_table', [
				'tests_table.id' => ['=', $this->model->table_name.'.id', false]
			])
			->limit(2)
			->execute()
			->get_result();
		$this->assertCount(2, $result);
	}

	/**
	 * @covers MysqlAdapter::group_by
	 */
	public function test_group_by() {
		$result = $this->model->select()
			->group_by(['id'])
			->limit(2)
			->execute()
			->get_result();
		$this->assertCount(2, $result);
	}

	/**
	 * @covers MysqlAdapter::order_by
	 */
	public function test_order_by() {
		$result = $this->model->select()
			->order_by(['id DESC'])
			->limit(2)
			->execute()
			->get_result();
		$this->assertCount(2, $result);
	}

	/**
	 * @covers MysqlAdapter::having
	 */
	public function test_having() {
		$result = $this->model->select()
			->having(['id' => ['=', 'min(id)', false]])
			->execute()
			->get_arrays();
		$this->assertCount(1, $result);

		$result = $this->model->select()
			->having('id = min(id)')
			->execute()
			->get_array();
		$this->assertCount(4, $result);


	}

	/**
	 * @covers MysqlAdapter::execute
	 */
	public function test_execute() {
		$id = $this->model->execute('select id from '.$this->model->table_name.' limit 1')
			->get_result();
		$this->assertInternalType('int', (int)$id);

		$id = $this->model->select(['id'])
			->execute()
			->get_result();
		$this->assertInternalType('int', (int)$id);

		$this->model->insert($this->values_provider()[0][0])
			->execute();
	}

	/**
	 * @covers MysqlAdapter::get_insert_id
	 */
	public function test_get_insert_id() {
		$insert_id = $this->model->insert($this->values_provider()[0][0])
			->execute()
			->get_insert_id();
		$this->assertInternalType('int', (int)$insert_id);
	}

	/**
	 * @covers MysqlAdapter::get_arrays_from_statement
	 */
	public function test_get_arrays_from_statement() {
		$sth = $this->model->select()
			->limit(1)
			->execute()
			->get_statement();
		$this->assertCount(1, $this->model->get_arrays_from_statement($sth));
	}

	/**
	 * @covers MysqlAdapter::get_arrays
	 */
	public function test_get_arrays() {
		$result = $this->model->select()
			->limit(1)
			->execute()
			->get_arrays();
		$this->assertCount(1, $result);
	}

	/**
	 * @covers MysqlAdapter::data_to_arrays
	 */
	public function test_data_to_arrays() {
		$data = $this->model->select()
			->limit(1)
			->execute()
			->get_result();
		$this->assertCount(1, $this->model->data_to_arrays($data));

		$data = $this->model->select()
			->limit(2)
			->execute()
			->get_result();
		$this->assertCount(2, $this->model->data_to_arrays($data));

		$data = $this->model->select(['id'])
			->limit(1)
			->execute()
			->get_result();
		$this->assertCount(1, $this->model->data_to_arrays($data));

        $data = $this->model->select()
			->where(['id' => ['=', 0]])
			->execute()
			->get_result();
		$this->assertCount(0, $this->model->data_to_arrays($data));
	}

	/**
	 * @covers MysqlAdapter::get_array_from_statement
	 */
	public function test_get_array_from_statement() {
		$sth = $this->model->select()
			->limit(2)
			->execute()
			->get_statement();
		$this->assertCount(4, $this->model->get_array_from_statement($sth));
	}

	/**
	 * @covers MysqlAdapter::data_to_array
	 */
	public function test_data_to_array() {
		$data = $this->model->select()
			->limit(1)
			->execute()
			->get_result();
		$this->assertCount(4, $this->model->data_to_array($data));

		$data = $this->model->select()
			->limit(3)
			->execute()
			->get_result();
		$this->assertCount(4, $this->model->data_to_array($data));

		$data = $this->model->select(['id'])
			->limit(1)
			->execute()
			->get_result();
		$this->assertCount(1, $this->model->data_to_array($data));

		$data = $this->model->select()
            ->where(['id' => ['=', 0]])
            ->execute()
            ->get_result();
        $this->assertCount(0, $this->model->data_to_array($data));
	}

	/**
	 * @covers MysqlAdapter::get_array
	 */
	public function test_get_array() {
		$result = $this->model->select()
			->limit(1)
			->execute()
			->get_array();
		$this->assertCount(4, $result);

		$result = $this->model->select()
			->limit(2)
			->execute()
			->get_array();
		$this->assertCount(4, $result);

		$data = $this->model->select(['id'])
			->limit(1)
			->execute()
			->get_result();
		$this->assertCount(1, $this->model->data_to_array($data));
	}

	/**
	 * @covers MysqlAdapter::return_from_statement
	 */
	public function test_return_from_statement() {
		$sth = $this->model->select(['id'])
			->limit(1)
			->execute()
			->get_statement();
		$this->assertInternalType('int', (int)$this->model->return_from_statement($sth));

		$sth = $this->model->select()
			->limit(1)
			->execute()
			->get_statement();
		$this->assertCount(4, $this->model->return_from_statement($sth));

		$sth = $this->model->select()
			->limit(2)
			->execute()
			->get_statement();
		$this->assertCount(2, $this->model->return_from_statement($sth));

		$sth = $this->model->insert($this->values_provider()[0][0])
			->execute()
			->get_statement();
		$this->assertNull($this->model->return_from_statement($sth));

		$sth = $this->model->select(['id'])
			->where(['id' => ['=', 0]])
			->execute()
			->get_statement();
		$this->assertNull($this->model->return_from_statement($sth));

		$sth = $this->model->select(['id'])
			->limit(4)
			->execute()
			->get_statement();
		$this->assertInternalType('array', $this->model->return_from_statement($sth));
	}

	/**
	 * @covers MysqlAdapter::get_result
	 */
	public function test_get_result() {
		$id = $this->model->select(['id'])
			->execute()
			->get_result();
		$this->assertInternalType('int', (int)$id);
	}
}