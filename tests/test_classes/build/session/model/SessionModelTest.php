<?php
require_once ROOT_PATH.DS.'build'.DS.'session'.DS.'model'.DS.'sessionmodel.php';

/**
 * Class SessionModelTest
 *
 * @method bool assertInternalType($a,$b)
 * @method bool assertEquals($a,$b)
 * @method bool assertCount($a,$b)
 * @method bool assertNull($var)
 * @method bool assertFalse($var)
 * @method bool assertTrue($var)
 */
class SessionModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $model \Session\model\SessionModel
     */
    private $model;

    public function setUp() {
        if(empty($this->model)) {
            $this->model = new \Session\model\SessionModel();
        }
    }

    /**
     * @covers SessionModel::get_file
     */
	public function test_get_file() {
        $file = 'pfmextension://session/resource/session_model.json';
        $this->assertTrue(file_exists($file));

        $method = new ReflectionMethod($this->model, 'get_file');
        $this->assertEquals($file, $method->invoke($this->model));
    }
}
 