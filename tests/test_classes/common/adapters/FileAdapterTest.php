<?php

class FakeFileAdapter extends \common\adapters\FileAdapter {

    public function __construct() {
        parent::__construct(ROOT_PATH.DS.'tests'.DS.'resource'.DS.'fake_data.json');
        $this->data = array_slice($this->data, 0, 2);
    }

    public function save() {
        parent::save();
    }
}

class FileAdapterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $model FakeFileAdapter
     */
    private $model;

    public function setUp() {
        $this->model = new FakeFileAdapter();
    }

    /**
     * @covers FileAdapter::__construct
     */
    public function test_construct() {
        new FakeFileAdapter();
    }

    /**
     * @covers FileAdapter::insert
     */
    public function test_insert() {
        foreach([1,2,3] as $i) {
            self::assertInternalType('int', $this->model->insert([
                'field' => uniqid('value', true)
            ]));
        }
        $this->model->save();
    }

    /**
     *  @covers FileAdapter::select
     */
    public function test_select() {
        self::assertTrue($this->model->select() instanceof \YaLinqo\Enumerable);
    }

    public function test_update() {
        $this->model->update(1, [
            'field' => uniqid('value', true)
        ]);
    }

    public function test_delete() {
        $count = $this->model->select()->count();
        $this->model->delete($count);
        self::assertEquals($this->model->select()->count(), $count-1);
    }

    /**
     * @covers FileAdapter::save
     */
    public function test_save() {
        $method = new ReflectionMethod($this->model, 'save');
        $method->setAccessible(true);
        $method->invoke($this->model);
    }
}
