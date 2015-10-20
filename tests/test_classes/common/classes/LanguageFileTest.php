<?php

use common\classes\LanguageFile;

class LanguageFileTest extends PHPUnit_Framework_TestCase {
    use \common\traits\TraitJSON;

    /**
     * @var $obj LanguageFile
     */
    private $obj;

    private $base_path;

    private $file;

    public function setUp() {
        $this->base_path = ROOT_PATH.DS.'tests'.DS.'resource';
        $this->file = 'fake_language_file.json';
        $this->obj = new LanguageFile($this->file, $this->base_path);
    }

    /**
     * @covers LanguageFile::__construct
     */
    public function test_construct() {
        new LanguageFile($this->file, $this->base_path);
    }

    /**
     * @covers LanguageFile::get_data
     */
    public function test_get_data() {
        self::assertEquals(json_decode(file_get_contents($this->base_path.DS.$this->file), true),
            $this->obj->get_data());
    }

    /**
     * @covers LanguageFile::__toString
     */
    public function test_to_string() {
        self::assertInternalType('string', (string)$this->obj);
    }
}