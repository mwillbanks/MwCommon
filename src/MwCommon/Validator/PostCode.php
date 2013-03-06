<?php
/**
 * MwCommon
 */

namespace MwCommon\Validator;

use Zend\I18n\Validator\PostCode as ZfPostCode;

/**
 * Post Code Validator
 * Based off ISO-3611-1 countries.
 */
class PostCode extends ZfPostCode
{
    const INVALID     = 'postcodeInvalid';
    const UNSUPPORTED = 'postcodeUnsupported';
    const NO_MATCH    = 'postcodeNoMatch';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID     => 'Invalid type given. String or integer expected',
        self::UNSUPPORTED => 'The country provided is currently unsupported',
        self::NO_MATCH    => 'The input does not appear to be a postal code',
    );

    /**
     * @var string
     */
    protected $country;

    /**
     * Get Country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set Country
     *
     * @param  string   $country
     * @return PostCode
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Constructor for the PostCode validator
     *
     * Accepts a string country.
     *
     * @param array|Traversable $options
     */
    public function __construct($options = array())
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (array_key_exists('country', $options)) {
            $this->setCountry($options['country']);
        }

        parent::__construct($options);
    }

    /**
     * Returns true if and only if $value matches post code format
     *
     * @param  string $value
     * @param  array  $context
     * @return bool
     */
    public function isValid($value = null, $context = null)
    {
        if (!is_scalar($value)) {
            $this->error(self::INVALID);

            return false;
        }
        $this->setValue($value);

        $country = $this->getCountry();
        if (!isset(self::$postCodeRegex[$country])) {
            if (isset($context[$country])) {
                $country = $context[$country];
            }

            if (!isset(self::$postCodeRegex[$country])) {
                $this->error(self::UNSUPPORTED);

                return false;
            }
        }

        $format = self::$postCodeRegex[$country];
        if ($format[0] !== '/') {
            $format = '/^' . $format;
        }
        if ($format[strlen($format) - 1] !== '/') {
            $format .= '$/';
        }

        if (!preg_match($format, $value)) {
            $this->error(self::NO_MATCH);

            return false;
        }

        return true;
    }
}
