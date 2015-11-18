<?php

namespace test_classes\common\helpers;

use common\classes\Error;
use common\helpers\ReflectionHelper;

/**
 * @decorate common\decorators\AnnotationsDecorator
 */
class Annotated {
    /**
     * @credentials admin
     */
    public function method() {}
}

class ReflectionHelperTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers common\helpers\ReflectionHelper::get_class_annotations
     * @covers common\helpers\ReflectionHelper::parse_doc_comment
     */
    public function test_get_class_annotations() {
        self::assertEquals(
            ReflectionHelper::get_class_annotations(Annotated::class),
            [
                [
                    'name' => 'decorate',
                    'value' => 'common\decorators\AnnotationsDecorator'
                ]
            ]
        );
    }

    /**
     * @covers common\helpers\ReflectionHelper::get_method_annotations
     * @covers common\helpers\ReflectionHelper::parse_doc_comment
     */
    public function test_get_method_annotations() {
        self::assertEquals(
            ReflectionHelper::get_method_annotations(Annotated::class, 'method'),
            [
                [
                    'name' => 'credentials',
                    'value' => 'admin'
                ]
            ]
        );
    }
}
