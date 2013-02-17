<?php
/**
 * MwCommon
 */

namespace MwCommonTest\Validator;

use MwCommon\Validator\Iban;

class IbanTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new Iban();
    }

    public function testExpectedResults()
    {
        $valuesExpected = array(
            'AMBKDKKKXXX' => true,
            'DABADKKKCUS' => true,
            'REPUMGMGRTG' => true,
            '12APPAm#332' => false,
            'ABCDEFGH'    => true,
            '12112221'    => false,
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
