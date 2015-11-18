<?php

namespace test_classes\common\collections;


use common\adapters\MysqlAdapter;
use common\classes\Error;
use common\collections\BaseCollection;
use common\models\BaseModel;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class BaseCollectionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $collection BaseCollection
     */
    private $collection;

    /**
     * @var $adapter MysqlAdapter
     */
    private $adapter;

    public function setUp() {
        $this->adapter = new MysqlAdapter('tests_table2');
        $this->collection = new BaseCollection(BaseModel::class);
    }

    /**
     * @covers common\collections\BaseCollection::__construct
     */
    public function test_construct() {
        new BaseCollection(BaseModel::class);
    }

    /**
     * @covers common\collections\BaseCollection::load
     * @covers common\collections\BaseCollection::count
     */
    public function test_load() {
        $this->collection->load($this->adapter->select()->limit(3)->execute()->get_result());
        self::assertTrue($this->collection->count() === 3);
    }

    /**
     * @covers common\collections\BaseCollection::get
     */
    public function test_get() {
        $this->collection->load($this->adapter->select()->limit(3)->execute()->get_result());
        self::assertTrue($this->collection->get(1) instanceof BaseModel);
    }

    /**
     * @covers common\collections\BaseCollection::add
     */
    public function test_add() {
        $this->collection->load($this->adapter->select()->limit(3)->execute()->get_result());
        $this->collection->add(new BaseModel(
            $this->adapter->select()->order_by('id DESC')->limit(1)->execute()->get_result()
        ));
        self::assertTrue($this->collection->count() === 4);
    }

    /**
     * @covers common\collections\BaseCollection::one
     */
    public function test_one() {
        $this->collection->load($this->adapter->select()->limit(3)->execute()->get_result());
        self::assertTrue($this->collection->one()->get_id() == 1);
    }

    /**
     * @covers common\collections\BaseCollection::rewind
     * @covers common\collections\BaseCollection::current
     * @covers common\collections\BaseCollection::key
     * @covers common\collections\BaseCollection::next
     * @covers common\collections\BaseCollection::valid
     */
    public function test_iterator() {
        $this->collection->load($this->adapter->select()->limit(3)->execute()->get_result());
        foreach ($this->collection as $key=>$model) {
            self::assertTrue($model instanceof BaseModel);
        }

    }

    /**
     * @covers common\collections\BaseCollection::offsetSet
     * @covers common\collections\BaseCollection::offsetGet
     * @covers common\collections\BaseCollection::offsetExists
     * @covers common\collections\BaseCollection::offsetUnset
     * @expectedException InvalidArgumentException
     */
    public function test_array_access() {
        $this->collection->load($this->adapter->select()->limit(3)->execute()->get_result());
        self::assertTrue($this->collection[1] instanceof BaseModel);

        $this->collection[3] = new BaseModel(
            $this->adapter->select()->order_by('id DESC')->limit(1)->execute()->get_result()
        );
        self::assertTrue($this->collection[3] instanceof BaseModel);

        unset($this->collection[3]);

        $this->collection[] = new BaseModel(
            $this->adapter->select()->order_by('id DESC')->limit(1)->execute()->get_result()
        );

        self::assertTrue(isset($this->collection[1]));

        self::assertNull($this->collection[25]);

        $this->collection[] = new \ArrayIterator([1,2,3]);
    }

    /**
     * @covers common\collections\BaseCollection::to_array
     */
    public function test_to_array() {
        $this->collection->load($this->adapter->select()->limit(3)->execute()->get_result());
        self::assertTrue($this->collection[1] instanceof BaseModel);

        array_map(function($el) {
            self::assertTrue(is_array($el));
        }, $this->collection->to_array());
    }
}
