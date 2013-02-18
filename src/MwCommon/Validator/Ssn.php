<?php
/**
 * MwCommon
 */

namespace MwCommon\Validator;

use Zend\Validator\AbstractValidator;

class Ssn extends AbstractValidator
{

    const NO_MATCH  = 'ssnNoMatch';
    const TOO_SHORT = 'ssnTooShort';
    const TOO_LONG  = 'ssnTooLong';
    const INVALID   = 'ssnInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NO_MATCH       => 'The input does not match the SSN format',
        self::TOO_SHORT      => 'The input must be 9 characters',
        self::TOO_LONG       => 'The input must be 9 characters',
        self::INVALID        => 'Invalid type given.  Numeric string expected',
    );

    /**
     * Simple Regular Expression Test for Format
     *
     * @var string
     */
    protected $pattern = '/^(\d{3})(\d{2})(\d{4})$/';

    /**
     * Returns true if and only if $value matches iban format
     *
     * @todo   port php-iban library for more complete validation
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_scalar($value) || !is_numeric($value)) {
            $this->error(self::INVALID);
            return false;
        }

        $length = strlen($value);
        if ($length < 9) {
            $this->error(self::TOO_SHORT);
            return false;
        }

        if ($length > 9) {
            $this->error(self::TOO_LONG);
            return false;
        }

        if (!preg_match($this->pattern, $value, $matches)) {
            $this->error(self::NO_MATCH);
            return false;
        }

        // no digit group can be all 0's
        foreach ($matches as $match) {
            if (0 === (int) $match) {
                $this->error(self::NO_MATCH);
                return false;
            }
        }

        // invalid first digit groups 666 or 900-999
        if ($matches[1] == 666 || $matches[1] >= 900) {
            $this->error(self::NO_MATCH);
            return false;
        }

        // invalid for advertisements
        if ($value >= 987654320 && $value <= 987654329) {
            $this->error(self::NO_MATCH);
            return false;
        }
        // handle famous instance of 1938
        if ($value == "078051120") {
            $this->error(self::NO_MATCH);
            return false;
        }

        return true;
    }
}
