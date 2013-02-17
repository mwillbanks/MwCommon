<?php
/**
 * MwCommon
 */

namespace MwCommonTest\Validator;

use MwCommon\Validator\RoutingTransitNumber;

class RoutingTransitNumberTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new RoutingTransitNumber();
    }

    public function testExpectedResults()
    {
        $valuesExpected = array(
            '054001518' => true,
            '254074468' => true,
            '231133311' => false,
            '122211'    => false,
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
