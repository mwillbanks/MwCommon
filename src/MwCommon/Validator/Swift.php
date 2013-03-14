<?php
/**
 * MwCommon
 */

namespace MwCommon\Validator;

use Zend\Validator\AbstractValidator;

class Swift extends AbstractValidator
{

    const NO_MATCH       = 'swiftNoMatch';
    const INVALID        = 'swiftInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NO_MATCH       => 'The input does not match the SWIFT-BIC format',
        self::INVALID        => 'Invalid type given.  String expected',
    );

    /**
     * Regular Expression pattern
     * @var string
     */
    protected $regex = '/^([A-Z]{4})([A-Z]{2})([A-Z0-9]{2})([A-Z0-9]{3})?$/';

    /**
     * ISO 3611 Country Code
     * @var string
     */
    protected $country = null;

    /**
     * Constructor for the Swift validator
     *
     * Options
     * - country | string | field or value
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
     * @param  string $country
     * @return Iban
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Returns true if and only if $value matches iban format
     *
     * @param  string $value
     * @param  array  $context
     * @return bool
     */
    public function isValid($value = null, $context = null)
    {
        if (!is_string($value)) {
            $this->error(self::INVALID);

            return false;
        }

        if (!preg_match($this->regex, $value, $matches)) {
            $this->error(self::NO_MATCH);

            return false;
        }

        $country = $this->getCountry();
        if ($country !== null) {
            if (isset($context[$country])) {
                $country = $context[$country];
            }
            if (strcasecmp($matches[2], $country) !== 0) {
                $this->error(self::NO_MATCH);

                return false;
            }
        }

        return true;
    }
}
