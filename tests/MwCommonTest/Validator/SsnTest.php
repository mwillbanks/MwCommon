<?php
/**
 * MwCommon
 */

namespace MwCommonTest\Validator;

use MwCommon\Validator\Ssn;

class SsnTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new Ssn();
    }

    public function testExpectedResults()
    {
        $valuesExpected = array(
            // check 0 digit groups
            '000000000'  => false,
            '000121234'  => false,
            '123001234'  => false,
            '123120000'  => false,
            // check non-issues first group
            '666121234'  => false,
            '900121234'  => false,
            '950121234'  => false,
            '999121234'  => false,
            // check advertisements
            '987654320'  => false,
            '987654325'  => false,
            '987654329'  => false,
            // check specialty
            '078051120'  => false,
            // check length
            '1231231234' => false,
            '12312123'   => false,
            // check non-numeric
            '12#1212$4'  => false,
            '12d121a11'  => false,
            // check proper
            '123121234'  => true,
            '412211212'  => true,
            '001010001'  => true,
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
