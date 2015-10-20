<?php

use Session\mappers\SessionMapper;

class SessionMapperTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $mapper SessionMapper
     */
    private $mapper;

    public function setUp() {
        \common\classes\Application::get_class(Session::class);
        $this->mapper = \common\classes\Application::get_class(SessionMapper::class);
    }

    /**
     * @covers Session\mappers\SessionMapper::get_adapter
     */
    public function test_get_adapter() {
        self::assertTrue($this->mapper->get_adapter() instanceof \common\adapters\MysqlAdapter);
    }

    /**
     * @covers Session\mappers\SessionMapper::find_by_id
     */
    public function test_find_by_id() {
        \common\classes\Application::get_class(Session::class);
        $this->mapper = \common\classes\Application::get_class(SessionMapper::class);
        $adapter = $this->mapper->get_adapter();
        $id = $adapter->execute('SELECT id from session ORDER BY id DESC LIMIT 1')->get_result();
        $record = $this->mapper->find_by_id($id);
        self::assertEquals($id, $record->id);
        return [[$record]];
    }

    /**
     * @param \Session\models\SessionModel $record
     * @dataProvider test_find_by_id
     * @covers Session\mappers\SessionMapper::save
     */
    public function test_save($record) {
        $this->mapper->save($record);

        $model = new \Session\models\SessionModel([
            'uid' => 0,
            'vars' => []
        ]);
        $this->mapper->save($model);
    }
}
