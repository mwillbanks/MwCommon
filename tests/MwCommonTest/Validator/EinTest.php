<?php
/**
 * MwCommon
 */

namespace MwCommonTest\Validator;

use MwCommon\Validator\Ein;

class EinTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new Ein();
    }

    public function testExpectedResults()
    {
        $valuesExpected = array(
            // check non-valid prefixes
            '070000000'  => false,
            '170000000'  => false,
            '280000000'  => false,
            '490000000'  => false,
            '640000000'  => false,
            '780000000'  => false,
            '890000000'  => false,
            '960000000'  => false,
            // check length
            '1231231234' => false,
            '12312123'   => false,
            // check non-numeric
            '12#1212$4'  => false,
            '12d121a11'  => false,
            // check proper
            '010000000'  => true,
            '808080808'  => true,
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
