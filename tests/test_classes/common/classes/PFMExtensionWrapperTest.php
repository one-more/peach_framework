<?php

use common\classes\PFMExtensionWrapper;

class PFMExtensionWrapperTest extends PHPUnit_Framework_TestCase {
	private $test_file;
	private $test_rename_file;
	private $test_text = 'test text';

	public static function setUpBeforeClass() {
		if(!in_array('pfmextension', stream_get_wrappers())) {
			stream_wrapper_register('pfmextension', PFMExtensionWrapper::class);
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
     * @covers common\classes\PFMExtensionWrapper::create_phar_data
     */
    public function test_create_phar_data() {
        $wrapper = new PFMExtensionWrapper;
		$parse_path = new ReflectionMethod(PFMExtensionWrapper::class, 'parse_path');
        $parse_path->setAccessible(true);
        $parse_path->invoke($wrapper, 'pfmextension://system');
        $method = new ReflectionMethod(PFMExtensionWrapper::class, 'create_phar_data');
        $method->setAccessible(true);
        $result = $method->invoke($wrapper);
        self::assertInstanceOf('PharData', $result);
    }

	/**
	 * @covers common\classes\PFMExtensionWrapper::stream_write
	 */
	public function test_write() {
		$bytes = file_put_contents($this->test_file, $this->test_text);
		self::assertEquals(9, $bytes);
	}

	/**
	 * @covers common\classes\PFMExtensionWrapper::stream_read
	 */
	public function test_read() {
		$text = file_get_contents($this->test_file);
		self::assertEquals($text, $this->test_text);
	}

	/**
	 * @return resource
	 * @covers common\classes\PFMExtensionWrapper::stream_open
	 */
	public function test_fopen() {
		$fp = fopen($this->test_file, 'rb');
		self::assertInternalType('resource', $fp);
		return $fp;
	}

	/**
	 * @param $fp
	 * @depends test_fopen
	 * @covers common\classes\PFMExtensionWrapper::stream_tell
	 */
	public function test_ftell($fp) {
		self::assertInternalType('resource', $fp);
		self::assertEquals(0, ftell($fp));
		return $fp;
	}

	/**
	 * @covers common\classes\PFMExtensionWrapper::url_stat
	 */
	public function test_stat() {
		self::assertInternalType('array', lstat('pfmextension://system/system.php'));
		self::assertInternalType('array', stat('pfmextension://system/system.php'));
		self::assertInternalType('array', stat('pfmextension://system/undefined.php'));
	}

	/**
	 * @param $fp
	 * @depends test_ftell
	 * @covers common\classes\PFMExtensionWrapper::stream_eof
	 */
	public function test_eof($fp) {
		self::assertInternalType('resource', $fp);
		self::assertFalse(feof($fp));
		return $fp;
	}

	/**
	 * @param $fp
	 * @depends test_eof
	 * @covers common\classes\PFMExtensionWrapper::stream_stat
	 */
	public function test_fstat($fp) {
		self::assertInternalType('resource', $fp);
		self::assertInternalType('array', fstat($fp));
		return $fp;
	}

	/**
	 * @param $fp
	 * @depends test_fstat
	 */
	public function test_fclose($fp) {
		self::assertInternalType('resource', $fp);
		self::assertTrue(fclose($fp));
	}

	/**
	 * @covers common\classes\PFMExtensionWrapper::rename
	 */
	public function test_rename() {
		self::assertTrue(rename($this->test_file, $this->test_rename_file));
		self::assertTrue(rename($this->test_rename_file, $this->test_file));
	}

	/**
	 * @covers common\classes\PFMExtensionWrapper::unlink
	 */
	public function test_unlink() {
		self::assertTrue(unlink($this->test_file));
	}

	/**
	 * @covers common\classes\PFMExtensionWrapper::stream_metadata
	 */
	public function test_touch() {
		self::assertTrue(touch($this->test_file));
	}

	/**
	 * @covers common\classes\PFMExtensionWrapper::stream_metadata
	 */
	public function test_chown() {
		self::assertFalse(chown($this->test_file, 'www-data'));
		self::assertFalse(chown($this->test_file, posix_getpwnam('www-data')['uid']));
	}

	/**
	 * @covers common\classes\PFMExtensionWrapper::stream_metadata
	 */
	public function test_chgrp() {
		self::assertFalse(chgrp($this->test_file, 'www-data'));
		self::assertFalse(chgrp($this->test_file, posix_getpwnam('www-data')['gid']));
	}

	/**
	 * @covers common\classes\PFMExtensionWrapper::stream_metadata
	 */
	public function test_chmod() {
		self::assertTrue(chmod($this->test_file, 0777));
	}

	public function test_end_metadata() {
		self::assertTrue(unlink($this->test_file));
	}

	/**
	 * @covers common\classes\PFMExtensionWrapper::mkdir
	 */
	public function test_mkdir() {
		mkdir('pfmextension://system'.DS.'wrapper_test_dir');
	}

	/**
	 * @covers common\classes\PFMExtensionWrapper::rmdir
	 */
	public function test_rmdir() {
		rmdir('pfmextension://system'.DS.'wrapper_test_dir');
	}
}