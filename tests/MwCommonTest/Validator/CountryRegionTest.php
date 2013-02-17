<?php
/**
 * MwCommon
 */

namespace MwCommonTest\Validator;

use MwCommon\Validator\CountryRegion;

class CountryRegionTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new CountryRegion();
    }

    public function testExpectedResults()
    {
        $valuesExpected = array(
            'US' => array(
                'US-CA' => true,
                'US-ZZ' => false,
                'US-0'  => false,
            ),
            'BF' => array(
                'BAL' => false,
                'BF-BAL' => true,
                '00' => false,
            ),
            'DE' => array(
                'DE-BB' => true,
                'DA-MV' => false,
                'MW' => false,
            ),
        );

        foreach ($valuesExpected as $country => $values) {
            $this->validator->setCountry($country);
            foreach ($values as $value => $expected) {
                $this->assertEquals($expected, $this->validator->isValid($value));
            }
        }
    }
}
