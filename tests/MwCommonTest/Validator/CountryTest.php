<?php
/**
 * MwCommon
 */

namespace MwCommonTest\Validator;

use MwCommon\Validator\Country;

class CountryTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new Country();
    }

    public function testExpectedResults()
    {
        $valuesExpected = array(
            '00' => false,
            'US' => true,
            'GB' => true,
            'DE' => true,
            'ZZ' => false,
            'B'  => false,
            'ZA' => true,
        );
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
