<?php

use common\adapters\MysqlAdapter;
use common\helpers\ArrayHelper;

class MysqlAdapterTestImpl extends MysqlAdapter {

    public $table_name;
	
    public function __construct() {
		parent::__construct('tests_table2');
	}

	public function get_configuration() {
		return parent::get_configuration();
	}

	public function parse_conditions($conditions) {
		parent::parse_conditions($conditions);
	}

	public function get_query() {
		return $this->query;
	}

	public function get_statement() {
		return $this->statement;
	}

	public function get_arrays_from_statement(PDOStatement $sth) {
        $method = new ReflectionMethod(MysqlAdapter::class, 'get_arrays_from_statement');
		$method->setAccessible(true);
        return $method->invoke($this, $sth);
	}

	public function data_to_arrays($data) {
        $method = new ReflectionMethod(MysqlAdapter::class, 'data_to_arrays');
        $method->setAccessible(true);
        return $method->invoke($this, $data);
	}

	public function get_array_from_statement(PDOStatement $sth) {
        $method = new ReflectionMethod(MysqlAdapter::class, 'get_array_from_statement');
        $method->setAccessible(true);
        return $method->invoke($this, $sth);
	}

	public function data_to_array($data) {
        $method = new ReflectionMethod(MysqlAdapter::class, 'data_to_array');
        $method->setAccessible(true);
        return $method->invoke($this, $data);
	}

	public function return_from_statement(PDOStatement $sth) {
        $method = new ReflectionMethod(MysqlAdapter::class, 'return_from_statement');
        $method->setAccessible(true);
        return $method->invoke($this, $sth);
	}
}

/**
 * Class MysqlAdapterTest
 *
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
	 * @covers common\adapters\MysqlAdapter::__construct
	 */
	public function test_create_model() {
		self::assertInternalType('object', new MysqlAdapterTestImpl());
	}

	/**
	 * @covers common\adapters\MysqlAdapter::get_configuration
	 */
	public function test_get_configuration() {
		self::assertTrue(ArrayHelper::is_assoc_array($this->model->get_configuration()));
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
	 * @covers common\adapters\MysqlAdapter::insert
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
	 * @covers common\adapters\MysqlAdapter::insert
	 * @expectedException InvalidArgumentException
	 */
	public function test_insert_no_fields() {
		$this->model->insert([])
			->execute();
	}

	/**
     * @param array $fields
	 * @covers common\adapters\MysqlAdapter::update
	 * @dataProvider values_provider
	 */
	public function test_update($fields) {
		static $update_id = 1;
		$this->model->update($fields)
			->where(['id'=> ['=', $update_id++, false]])
			->execute();
	}

	/**
	 * @covers common\adapters\MysqlAdapter::update
	 * @expectedException InvalidArgumentException
	 */
	public function test_update_no_fields() {
		$this->model->update([])
			->where(['id'=> ['=', 1, false]])
			->execute();
	}

	/**
	 * @covers common\adapters\MysqlAdapter::select
	 */
	public function test_select() {
		$id = $this->model->select(['id'])
			->where(['id' => ['=', 1]])
			->execute()
			->get_result();
		self::assertEquals(1, $id);

		$result = $this->model->select()
			->limit(1)
			->execute()
			->get_result();
		self::assertCount(4, $result);
	}

	/**
	 * @covers common\adapters\MysqlAdapter::delete
	 */
	public function test_delete() {
		$this->model->delete()
			->order_by(['id desc'])
			->limit(1)
			->execute();
	}

	/**
	 * @covers common\adapters\MysqlAdapter::where
	 */
	public function test_where() {
		$id = $this->model->select(['id'])
			->where(['id' => ['>', 1]])
			->limit(1)
			->execute()
			->get_result();
		self::assertInternalType('int', (int)$id);
		$row = $this->model->select()
			->where('id > 1')
			->limit(1)
			->execute()
			->get_result();
		self::assertInternalType('array', $row);
	}

	/**
	 * @covers common\adapters\MysqlAdapter::limit
	 */
	public function test_limit() {
		$result = $this->model->select()
			->limit(1)
			->execute()
			->get_result();
		self::assertCount(4, $result);
	}

	/**
	 * @covers common\adapters\MysqlAdapter::offset
	 */
	public function test_offset() {
		$result = $this->model->select()
			->limit(1)
			->offset(1)
			->execute()
			->get_result();
		self::assertCount(4, $result);
	}

	/**
	 * @covers common\adapters\MysqlAdapter::parse_conditions
	 */
	public function test_parse_conditions() {
		$this->model->parse_conditions([
			'id' => ['=', 1],
			'or' => ['id' => ['=', 2]],
			'and' => ['id' => ['<', 3, false]]
		]);
		self::assertEquals('id = ? or id = ? and id < 3', trim($this->model->get_query()));
	}

	/**
	 * @covers common\adapters\MysqlAdapter::join
	 */
	public function test_join() {
		$result = $this->model->select()
			->join('INNER', 'tests_table', [
				'tests_table.id' => ['=', $this->model->table_name.'.id', false]
			])
			->limit(2)
			->execute()
			->get_result();
		self::assertCount(2, $result);
	}

	/**
	 * @covers common\adapters\MysqlAdapter::group_by
	 */
	public function test_group_by() {
		$result = $this->model->select()
			->group_by(['id'])
			->limit(2)
			->execute()
			->get_result();
		self::assertCount(2, $result);
	}

	/**
	 * @covers common\adapters\MysqlAdapter::order_by
	 */
	public function test_order_by() {
		$result = $this->model->select()
			->order_by(['id DESC'])
			->limit(2)
			->execute()
			->get_result();
		self::assertCount(2, $result);
	}

	/**
	 * @covers common\adapters\MysqlAdapter::having
	 */
	public function test_having() {
		$result = $this->model->select()
			->having(['id' => ['=', 'min(id)', false]])
			->execute()
			->get_arrays();
		self::assertCount(1, $result);

		$result = $this->model->select()
			->having('id = min(id)')
			->execute()
			->get_array();
		self::assertCount(4, $result);


	}

	/**
	 * @covers common\adapters\MysqlAdapter::execute
	 */
	public function test_execute() {
		$id = $this->model->execute("select id from {$this->model->table_name} limit 1")
			->get_result();
		self::assertInternalType('int', (int)$id);

		$id = $this->model->select(['id'])
			->execute()
			->get_result();
		self::assertInternalType('int', (int)$id);

		$this->model->insert($this->values_provider()[0][0])
			->execute();
	}

	/**
	 * @covers common\adapters\MysqlAdapter::get_insert_id
	 */
	public function test_get_insert_id() {
		$insert_id = $this->model->insert($this->values_provider()[0][0])
			->execute()
			->get_insert_id();
		self::assertInternalType('int', (int)$insert_id);
	}

	/**
	 * @covers common\adapters\MysqlAdapter::get_arrays_from_statement
	 */
	public function test_get_arrays_from_statement() {
		$sth = $this->model->select()
			->limit(1)
			->execute()
			->get_statement();
		self::assertCount(1, $this->model->get_arrays_from_statement($sth));
	}

	/**
	 * @covers common\adapters\MysqlAdapter::get_arrays
	 */
	public function test_get_arrays() {
		$result = $this->model->select()
			->limit(1)
			->execute()
			->get_arrays();
		self::assertCount(1, $result);
	}

	/**
	 * @covers common\adapters\MysqlAdapter::data_to_arrays
	 */
	public function test_data_to_arrays() {
		$data = $this->model->select()
			->limit(1)
			->execute()
			->get_result();
		self::assertCount(1, $this->model->data_to_arrays($data));

		$data = $this->model->select()
			->limit(2)
			->execute()
			->get_result();
		self::assertCount(2, $this->model->data_to_arrays($data));

		$data = $this->model->select(['id'])
			->limit(1)
			->execute()
			->get_result();
		self::assertCount(1, $this->model->data_to_arrays($data));

        $data = $this->model->select()
			->where(['id' => ['=', 0]])
			->execute()
			->get_result();
		self::assertCount(0, $this->model->data_to_arrays($data));
	}

	/**
	 * @covers common\adapters\MysqlAdapter::get_array_from_statement
	 */
	public function test_get_array_from_statement() {
		$sth = $this->model->select()
			->limit(2)
			->execute()
			->get_statement();
		self::assertCount(4, $this->model->get_array_from_statement($sth));
	}

	/**
	 * @covers common\adapters\MysqlAdapter::data_to_array
	 */
	public function test_data_to_array() {
		$data = $this->model->select()
			->limit(1)
			->execute()
			->get_result();
		self::assertCount(4, $this->model->data_to_array($data));

		$data = $this->model->select()
			->limit(3)
			->execute()
			->get_result();
		self::assertCount(4, $this->model->data_to_array($data));

		$data = $this->model->select(['id'])
			->limit(1)
			->execute()
			->get_result();
		self::assertCount(1, $this->model->data_to_array($data));

		$data = $this->model->select()
            ->where(['id' => ['=', 0]])
            ->execute()
            ->get_result();
        self::assertCount(0, $this->model->data_to_array($data));
	}

	/**
	 * @covers common\adapters\MysqlAdapter::get_array
	 */
	public function test_get_array() {
		$result = $this->model->select()
			->limit(1)
			->execute()
			->get_array();
		self::assertCount(4, $result);

		$result = $this->model->select()
			->limit(2)
			->execute()
			->get_array();
		self::assertCount(4, $result);

		$data = $this->model->select(['id'])
			->limit(1)
			->execute()
			->get_result();
		self::assertCount(1, $this->model->data_to_array($data));
	}

	/**
	 * @covers common\adapters\MysqlAdapter::return_from_statement
	 */
	public function test_return_from_statement() {
		$sth = $this->model->select(['id'])
			->limit(1)
			->execute()
			->get_statement();
		self::assertInternalType('int', (int)$this->model->return_from_statement($sth));

		$sth = $this->model->select()
			->limit(1)
			->execute()
			->get_statement();
		self::assertCount(4, $this->model->return_from_statement($sth));

		$sth = $this->model->select()
			->limit(2)
			->execute()
			->get_statement();
		self::assertCount(2, $this->model->return_from_statement($sth));

		$sth = $this->model->insert($this->values_provider()[0][0])
			->execute()
			->get_statement();
		self::assertNull($this->model->return_from_statement($sth));

		$sth = $this->model->select(['id'])
			->where(['id' => ['=', 0]])
			->execute()
			->get_statement();
		self::assertNull($this->model->return_from_statement($sth));

		$sth = $this->model->select(['id'])
			->limit(4)
			->execute()
			->get_statement();
		self::assertInternalType('array', $this->model->return_from_statement($sth));
	}

	/**
	 * @covers common\adapters\MysqlAdapter::get_result
	 */
	public function test_get_result() {
		$id = $this->model->select(['id'])
			->execute()
			->get_result();
		self::assertInternalType('int', (int)$id);
	}
}