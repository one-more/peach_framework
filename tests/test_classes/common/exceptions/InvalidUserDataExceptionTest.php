<?php

namespace test_classes\common\exceptions;


class InvalidUserDataExceptionTest extends \PHPUnit_Framework_TestCase {

    public function test_construct() {
        $errors = [
            'field1' => 'error1'
        ];
        $exception = new \InvalidUserDataException($errors);

        self::assertEquals($errors, $exception->errors);
        self::assertEquals('field1: error1', trim($exception->getMessage()));
    }
}
