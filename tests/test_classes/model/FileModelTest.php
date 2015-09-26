<?php

class FakeFileModel extends FileModel {

    public function get_file() {
        return ROOT_PATH.DS.'tests'.DS.'resource'.DS.'fake_data.json';
    }

    public function save() {
        parent::save();
    }
}

class FileModelTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $model FakeFileModel
     */
    private $model;

    public function setUp() {
        if(empty($this->model)) {
            $this->model = new FakeFileModel();
        }
    }

    /**
     * @covers FileModel::__construct
     */
    public function test_construct() {
        new FakeFileModel();
    }

    /**
     * @covers FileModel::insert
     */
    public function test_insert() {
        foreach([1,2,3] as $i) {
            $this->assertInternalType('int', $this->model->insert([
                'field' => uniqid('value', true)
            ]));
        }
        $this->model->save();
    }

    /**
     *  @covers FileModel::select
     */
    public function test_select() {
        $this->assertTrue($this->model->select() instanceof \YaLinqo\Enumerable);
    }

    public function test_update() {
        $this->model->update(1, [
            'field' => uniqid('value', true)
        ]);
    }

    public function test_delete() {
        $count = $this->model->select()->count();
        $this->model->delete($count);
        $this->assertEquals($this->model->select()->count(), $count-1);
    }

    /**
     * @covers FileModel::save
     */
    public function test_save() {
        $method = new ReflectionMethod($this->model, 'save');
        $method->setAccessible(true);
        $method->invoke($this->model);
    }
}
