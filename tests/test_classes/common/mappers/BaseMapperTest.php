<?php

namespace test_classes\common\mappers;


use common\adapters\MysqlAdapter;
use common\mappers\BaseMapper;

class TestMapper extends BaseMapper {

    /**
     * @return MysqlAdapter
     */
    public function get_adapter() {
        return new MysqlAdapter('test_table2');
    }
}

class BaseMapperTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $mapper BaseMapper
     */
    private $mapper;

    public function setUp() {
        $this->mapper = new TestMapper();
    }

    /**
     * @covers common\mappers\BaseMapper::__construct
     */
    public function test_construct() {
        new TestMapper();
    }

    /**
     * @covers common\mappers\BaseMapper::set_adapter
     */
    public function test_set_adapter() {
        $this->mapper->set_adapter(new MysqlAdapter('tests_table2'));
    }

    /**
     * @covers common\mappers\BaseMapper::get_validation_errors
     */
    public function test_get_validation_errors() {
        self::assertNull($this->mapper->get_validation_errors());
    }
}
