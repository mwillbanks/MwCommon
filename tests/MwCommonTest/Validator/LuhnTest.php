<?php
/**
 * MwCommon
 */

namespace MwCommonTest\Validator;

use MwCommon\Validator\Luhn;

class LuhnTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new Luhn();
    }

    public function testExpectedResults()
    {
        $valuesExpected = array(
            '79927398710' => true,
            '79927398711' => true,
            '79927398712' => true,
            '046454286'   => true,
        );

        foreach ($valuesExpected as $value => $result) {
            $this->assertEquals($result, $this->validator->isValid($value));
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
