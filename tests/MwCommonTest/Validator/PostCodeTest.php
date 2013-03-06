<?php
/**
 * MwCommon
 */

namespace MwCommonTest\Validator;

use MwCommon\Validator\PostCode;

class PostCodeTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new PostCode();
    }

    public function testPostCodes()
    {
        $values = array(
            'US' => '01234',
            'AT' => '1000',
        );

        foreach ($values as $country => $value) {
            $this->validator->setCountry($country);
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
