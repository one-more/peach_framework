<?php

class PFMExtensionWrapperTest extends PHPUnit_Framework_TestCase {
	private $test_file;
	private $test_rename_file;
	private $test_text = 'test text';

	public static function setUpBeforeClass() {
		if(!in_array('pfmextension', stream_get_wrappers())) {
			stream_wrapper_register('pfmextension', 'PFMExtensionWrapper');
		}
	}

	public static function tearDownAfterClass() {
		$tests_dir = 'pfmextension://system'.DS.'test_files';
		if(is_dir($tests_dir)) {
			rmdir($tests_dir);
		}
	}

	public function setUp() {
		$extension_path = 'pfmextension://system'.DS.'test_files';
		$this->test_file = $extension_path.DS.'wrapper_test_file.txt';
		$this->test_rename_file = $extension_path.DS.'wrapper_test_file_renamed.txt';
	}

    /**
     * @covers PFMExtensionWrapper::create_phar_data
     */
    public function test_create_phar_data() {
        $wrapper = new PFMExtensionWrapper;
		$parse_path = new ReflectionMethod('PFMExtensionWrapper', 'parse_path');
        $parse_path->setAccessible(true);
        $parse_path->invoke($wrapper, 'pfmextension://system');
        $method = new ReflectionMethod('PFMExtensionWrapper', 'create_phar_data');
        $method->setAccessible(true);
        $result = $method->invoke($wrapper);
        $this->assertInstanceOf('PharData', $result);
    }

	/**
	 * @covers PFMExtensionWrapper::stream_write
	 */
	public function test_write() {
		$bytes = file_put_contents($this->test_file, $this->test_text);
		$this->assertEquals(9, $bytes);
	}

	/**
	 * @covers PFMExtensionWrapper::stream_read
	 */
	public function test_read() {
		$text = file_get_contents($this->test_file);
		$this->assertEquals($text, $this->test_text);
	}

	/**
	 * @return resource
	 * @covers PFMExtensionWrapper::stream_open
	 */
	public function test_fopen() {
		$fp = fopen($this->test_file, 'rb');
		$this->assertInternalType('resource', $fp);
		return $fp;
	}

	/**
	 * @param $fp
	 * @depends test_fopen
	 * @covers PFMExtensionWrapper::stream_tell
	 */
	public function test_ftell($fp) {
		$this->assertInternalType('resource', $fp);
		$this->assertEquals(0, ftell($fp));
		return $fp;
	}

	/**
	 * @covers PFMExtensionWrapper::url_stat
	 */
	public function test_stat() {
		$this->assertInternalType('array', lstat('pfmextension://system/system.php'));
		$this->assertInternalType('array', stat('pfmextension://system/system.php'));
		$this->assertInternalType('array', stat('pfmextension://system/undefined.php'));
	}

	/**
	 * @param $fp
	 * @depends test_ftell
	 * @covers PFMExtensionWrapper::stream_eof
	 */
	public function test_eof($fp) {
		$this->assertInternalType('resource', $fp);
		$this->assertFalse(feof($fp));
		return $fp;
	}

	/**
	 * @param $fp
	 * @depends test_eof
	 * @covers PFMExtensionWrapper::stream_stat
	 */
	public function test_fstat($fp) {
		$this->assertInternalType('resource', $fp);
		$this->assertInternalType('array', fstat($fp));
		return $fp;
	}

	/**
	 * @param $fp
	 * @depends test_fstat
	 */
	public function test_fclose($fp) {
		$this->assertInternalType('resource', $fp);
		$this->assertTrue(fclose($fp));
	}

	/**
	 * @covers PFMExtensionWrapper::rename
	 */
	public function test_rename() {
		$this->assertTrue(rename($this->test_file, $this->test_rename_file));
		$this->assertTrue(rename($this->test_rename_file, $this->test_file));
	}

	/**
	 * @covers PFMExtensionWrapper::unlink
	 */
	public function test_unlink() {
		$this->assertTrue(unlink($this->test_file));
	}

	/**
	 * @covers PFMExtensionWrapper::stream_metadata
	 */
	public function test_touch() {
		$this->assertTrue(touch($this->test_file));
	}

	/**
	 * @covers PFMExtensionWrapper::stream_metadata
	 */
	public function test_chown() {
		$this->assertFalse(chown($this->test_file, 'www-data'));
		$this->assertFalse(chown($this->test_file, posix_getpwnam('www-data')['uid']));
	}

	/**
	 * @covers PFMExtensionWrapper::stream_metadata
	 */
	public function test_chgrp() {
		$this->assertFalse(chgrp($this->test_file, 'www-data'));
		$this->assertFalse(chgrp($this->test_file, posix_getpwnam('www-data')['gid']));
	}

	/**
	 * @covers PFMExtensionWrapper::stream_metadata
	 */
	public function test_chmod() {
		$this->assertTrue(chmod($this->test_file, 0777));
	}

	public function test_end_metadata() {
		$this->assertTrue(unlink($this->test_file));
	}

	/**
	 * @covers PFMExtensionWrapper::mkdir
	 */
	public function test_mkdir() {
		mkdir('pfmextension://system'.DS.'wrapper_test_dir');
	}

	/**
	 * @covers PFMExtensionWrapper::rmdir
	 */
	public function test_rmdir() {
		rmdir('pfmextension://system'.DS.'wrapper_test_dir');
	}
}