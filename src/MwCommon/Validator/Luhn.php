<?php
/**
 * MwCommon
 */

namespace MwCommon\Validator;

use Zend\Validator\AbstractValidator;

class Luhn extends AbstractValidator
{
    const NO_MATCH  = 'luhnNoMatch';
    const INVALID   = 'luhnInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NO_MATCH => 'The input is not a valid Luhn format',
        self::INVALID  => 'Invalid type given.  Numeric string expected',
    );

    /**
     * Returns true if and only if $value matches luhn algorithm
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_scalar($value) || !is_numeric($value)) {
            $this->error(self::INVALID);

            return false;
        }

        for ($sum = 0, $i = 0; $i < strlen($value); $i++) {
            $sum += ($i % 2 === 0) ? $value[$i] : array_sum(str_split($value[$i] * 2));
        }

        return (($sum % 10) === 0);
    }
}
