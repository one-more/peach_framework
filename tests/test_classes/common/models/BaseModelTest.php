<?php

namespace test_classes\common\models;


use common\adapters\MysqlAdapter;
use common\models\BaseModel;

class BaseModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $model BaseModel
     */
    private $model;

    /**
     * @var $adapter MysqlAdapter
     */
    private $adapter;

    public function setUp() {
        $this->adapter = new MysqlAdapter('tests_table2');
        $this->model = new BaseModel(
            $this->adapter->select()->order_by('id desc')->limit(1)->execute()->get_result()
        );
    }

    /**
     * @cover common\models\BaseModel::__construct
     */
    public function test_construct() {
        new BaseModel(
            $this->adapter->select()->order_by('id desc')->limit(1)->execute()->get_result()
        );
    }

    /**
     * @cover common\models\BaseModel::__set
     * @expectedException \common\exceptions\NotExistedFieldAccessException
     */
    public function test_set() {
        $this->model->field1 = uniqid('', true);
        $this->model->field4 = uniqid('', true);
    }

    /**
     * @cover common\models\BaseModel::__get
     * @expectedException \common\exceptions\NotExistedFieldAccessException
     */
    public function test_get() {
        $this->model->field1;
        $this->model->field4;
    }

    /**
     * @cover common\models\BaseModel::set_field
     */
    public function test_set_field() {
        $this->model->set_field(uniqid('field', true), uniqid('', true));
    }

    /**
     * @cover common\models\BaseModel::load
     */
    public function test_load() {
        $this->model->load(
            $this->adapter->select()->limit(1)->execute()->get_result()
        );
    }

    /**
     * @cover common\models\BaseModel::to_array
     */
    public function test_to_array() {
        $fields = $this->adapter->select()->limit(1)->execute()->get_result();
        $this->model->load($fields);
        self::assertEquals($fields, $this->model->to_array());
    }

    /**
     * @cover common\models\BaseModel::get_id
     */
    public function test_get_id() {
        $fields = $this->adapter->select()->limit(1)->execute()->get_result();
        $this->model->load($fields);
        self::assertEquals(1, $this->model->get_id());
    }
}
