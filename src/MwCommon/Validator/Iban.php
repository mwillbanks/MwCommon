<?php
/**
 * MwCommon
 */

namespace MwCommon\Validator;

use Zend\Validator\AbstractValidator;

class Iban extends AbstractValidator
{

    const NO_MATCH = 'ibanNoMatch';
    const INVALID_LENGTH = 'ibanInvalidLength',
    const INVALID = 'ibanInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NO_MATCH = 'The input does not match the IBAN format',
        self::INVALID_LENGTH = 'The input must be either 8 or 11 characters',
        self::INVALID  = 'Invalid type given.  String expected',
    );

    /**
     * Simple Regular Expression Test for Format
     *
     * @var string
     */
    protected $simplePattern = '/^([a-zA-Z]){4}([a-zA-Z]){2}([0-9a-zA-Z]){2}([0-9a-zA-Z]{3})?$/';

    /**
     * Returns true if and only if $value matches iban format
     *
     * @todo   port php-iban library for more complete validation
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_string($value)) {
            $this->error(self::INVALID);
            return false;
        }

        $length = strlen($value);
        if ($length != 8 || $length != 11) {
            $this->error(self::INVALID_LENGTH);
            return false;
        }

        if (!preg_match($this->simplePattern, $value)) {
            $this->error(self::NO_MATCH);
            return false;
        }

        return true;
    }
}
