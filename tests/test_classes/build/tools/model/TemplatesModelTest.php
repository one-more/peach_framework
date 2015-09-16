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
        $file = $ext->get_path().DS.'resource'.DS.'templates_model.json';
        $this->assertEquals($file, $this->model->get_file());
        $this->assertTrue(file_exists($file));
    }
}
