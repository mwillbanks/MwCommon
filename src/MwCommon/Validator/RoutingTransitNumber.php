<?php
/**
 * MwCommon
 */

namespace MwCommon\Validator;

use Zend\Validator\AbstractValidator;

class RoutingTransitNumber extends AbstractValidator
{

    const CHECKSUM_MISMATCH = 'routingTransitNumberChecksumMismatch',
    const TOO_SHORT         = 'routingTransitNumberTooShort';
    const TOO_LONG          = 'routingTransitNumberTooLong';
    const INVALID           = 'routingTransitNumberInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::CHECKSUM_MISMATCH => 'The input given is not a routing transit number',
        self::TOO_SHORT         => 'The input must be 9 digits in length',
        self::TOO_LONG          => 'The input must be 9 digits in length',
        self::INVALID           => 'Invalid type given.  Numeric string expected',
    );

    /**
     * Returns true if and only if $value has a checksum match
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_string($value) || !is_numeric($value)) {
            $this->error(self::INVALID);
            return false;
        }

        if (strlen($value) > 9) {
            $this->error(self::TOO_LONG);
            return false;
        }

        if (strlen($value) < 9) {
            $this->error(self::TOO_SHORT);
            return false;
        }

        $checksum = 7 * ($value[0] + $value[3] + $value[6]);
        $checksum += 3 * ($value[1] + $value[4] + $value[7]);
        $checksum += 9 * ($value[2] + $value[5]);
        if ($value[8] != $checksum % 10) {
            $this->error(self::CHECKSUM_MISMATCH);
            return false;
        }
        return true;
    }
}
