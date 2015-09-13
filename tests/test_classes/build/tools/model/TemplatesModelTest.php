<?php
require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'model'.DS.'templatesmodel.php';

/**
 * Class TemplatesModelTest
 *
 * @method bool assertInternalType($a,$b)
 * @method bool assertCount($a,$b)
 * @method bool assertEquals($a,$b)
 * @method bool assertGreaterThan($a,$b)
 * @method bool assertNull($var)
 * @method bool assertFalse($var)
 * @method bool assertTrue($var)
 */
class TemplatesModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $model \Tools\model\TemplatesModel
     */
    private $model;

    public function setUp() {
        if(empty($this->model)) {
            $this->model = Application::get_class('\Tools\model\TemplatesModel');
        }
    }

    /**
     * @covers \Tools\model\TemplatesModel::get_file
     */
    public function test_get_file() {
        /**
         * @var $ext Tools
         */
        $ext = Application::get_class('Tools');
        $this->assertEquals($ext->get_path().DS.'resource'.DS.'templates_list.json', $this->model->get_file());
    }

    /**
     * @covers \Tools\model\TemplatesModel::get_list
     */
    public function test_get_list() {
        $this->assertInternalType('array', $this->model->get_list());
    }

    /**
     * @covers \Tools\model\TemplatesModel::get_one
     */
    public function test_get_one() {
        $this->assertGreaterThan(0, count($this->model->get_one(1)));

        $this->assertCount(0, $this->model->get_one(0));
    }
}
