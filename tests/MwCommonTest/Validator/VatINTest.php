<?php
/**
 * MwCommon
 */

namespace MwCommonTest\Validator;

use MwCommon\Validator\VatIN;

class VatINTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new VatIN();
    }

    public function testVatINNumbers()
    {
        $values = array(
            'IE6388047V', // GOOGLE IRELAND : VIES
            'CA12345678', // FORMAT TEST
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
