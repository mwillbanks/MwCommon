<?php
/**
 * MwCommon
 */

namespace MwCommon\Validator;

use Zend\Validator\AbstractValidator;

class Ein extends AbstractValidator
{

    const NO_MATCH  = 'einNoMatch';
    const TOO_SHORT = 'einTooShort';
    const TOO_LONG  = 'einTooLong';
    const INVALID   = 'einInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NO_MATCH       => 'The input does not match the EIN format',
        self::TOO_SHORT      => 'The input must be 9 characters',
        self::TOO_LONG       => 'The input must be 9 characters',
        self::INVALID        => 'Invalid type given.  Numeric string expected',
    );

    /**
     * Valid EAN prefix ranges (int)
     *
     * @link http://www.irs.gov/Businesses/Small-Businesses-&-Self-Employed/How-EINs-are-Assigned-and-Valid-EIN-Prefixes
     * @var array
     */
    protected $prefix = array(
        array(1, 6),
        array(10, 16),
        array(20, 27),
        array(30, 39),
        array(40, 48),
        array(50, 59),
        array(60, 63),
        array(65, 68),
        array(71, 77),
        array(80, 88),
        array(90, 95),
        array(98, 99),
    );

    /**
     * Simple Regular Expression Test for Format
     *
     * @var string
     */
    protected $pattern = '/^(\d{2})(\d{7})$/';

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

        // check for EIN prefixes
        $intPrefix = (int) $matches[1];
        foreach ($this->prefix as $check) {
            if ($intPrefix >= $check[0] && $intPrefix <= $check[1]) {
                return true;
            }
        }

        $this->error(self::NO_MATCH);

        return false;
    }
}
