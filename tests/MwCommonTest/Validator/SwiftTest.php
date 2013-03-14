<?php
/**
 * MwCommon
 */

namespace MwCommonTest\Validator;

use MwCommon\Validator\Swift;

class SwiftTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new Swift();
    }

    public function testSwiftCodes()
    {
        $values = array(
            'DEUTDEFF500',
            'NEDSZAJJXXX',
            'NEDSZAJJ',
            'UNCRIT2B912',
            'DSBACNBXSHA',
        );

        foreach ($values as $value) {
            $this->assertTrue($this->validator->isValid($value));
        }
    }

    public function testInvalidTypes()
    {
        $values = array(
            array(),
            new \stdClass,
        );

        foreach ($values as $value) {
            $this->assertFalse($this->validator->isValid($value));
        }
    }
}
