<?php

namespace test_classes\common\traits;

use common\traits\TraitJSON;

class JSON {
    use TraitJSON;
}

class TraitJSONTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $obj JSON
     */
    private $obj;

    public function setUp() {
        $this->obj = new JSON();
    }

    /**
     * @covers common\traits\TraitJSON::array_to_json_string
     */
    public function test_array_to_json() {
        $this->obj->array_to_json_string(
            [
                [
                    'el' => uniqid('', true),
                    'el2' => [
                        'el3' => uniqid('', true),
                        true,
                        false,
                        new \ArrayIterator([
                            uniqid('', true),
                            uniqid('', true),
                            uniqid('', true)
                        ])
                    ]
                ],
                [uniqid('', true)],
                uniqid('', true),
                'field' => [true, false, [1,2,3], new \ArrayIterator([1,2,3])],
                function() {},
                fopen(WEB_ROOT.DS.'error.log', 'r')
            ]
        );
    }
}
