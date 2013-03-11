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
            'US' => array(
                '054001518' => true,
                '254074468' => true,
                '231133311' => false,
                '122211'    => false,
            ),
            'CA' => array(
                '15892100'  => true,
                '12345400'  => false,
                '02913700'  => false,
                '01010001'  => true,
                '1010101'   => false,
                '101011010' => false,
            ),
        );

        foreach ($valuesExpected as $country => $value) {
            $this->validator->setCountry($key);
            foreach ($value as $transit => $check) {
                $this->assertEquals($this->validator->isValid($transit), $check);
            }
        }
    }

    public function testNoCountry()
    {
        $this->assertFalse($this->validator->isValid('054001518'));
        $this->assertFalse($this->validator->isValid('15892100'));
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
