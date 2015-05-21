<?php

require_once '../initialize.php';

class ApplicationTest extends PHPUnit_Framework_TestCase {

	public function test_load_extension() {
		$extensions = glob(ROOT_PATH.DS.'extensions'.DS.'*');
		foreach($extensions as $extension) {
			$name = ucfirst(explode('.', basename($extension))[0]);
			$this->assertTrue(Application::load_extension($name));
		}
	}
}