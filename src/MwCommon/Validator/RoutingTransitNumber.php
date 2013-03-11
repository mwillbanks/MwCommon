<?php
/**
 * MwCommon
 */

namespace MwCommon\Validator;

use Zend\Validator\AbstractValidator;

class RoutingTransitNumber extends AbstractValidator
{

    const NO_MATCH    = 'routingTransitNumberNoMatch';
    const UNSUPPORTED = 'routingTransitNumberUnsupported';
    const TOO_SHORT   = 'routingTransitNumberTooShort';
    const TOO_LONG    = 'routingTransitNumberTooLong';
    const INVALID     = 'routingTransitNumberInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NO_MATCH    => 'The input given is not a routing transit number',
        self::UNSUPPORTED => 'The country provided is currently unsupported',
        self::TOO_SHORT   => 'The input must was too short',
        self::TOO_LONG    => 'The input must was too long',
        self::INVALID     => 'Invalid type given.  Numeric string expected',
    );

    /**
     * Countries that support routing transit numbers
     * @var array
     */
    protected $countrySupported = array('US', 'CA');

    /**
     * Country rules to apply by default checks all.
     * @var string
     */
    protected $country;

    /**
     * ABA Table
     * @var array
     */
    protected $aba = array(
        'US' => array(
            array(0, 12),
            array(21, 32),
            array(61, 72),
            array(80, 80),
        ),
        'CA' => array(
            array(1, 399),
            array(500, 699),
            array(800, 999),
        ),
    );

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
     * @param  string               $country
     * @return RoutingTransitNumber
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Returns true if and only if $value has a checksum match
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value, $context)
    {
        if (!is_scalar($value) || !is_numeric($value)) {
            $this->error(self::INVALID);

            return false;
        }

        $country = $this->getCountry();
        if (!in_array($country, $this->countrySupported)) {
            $this->error(self::UNSUPPORTED);

            return false;
        }

        if ($country == 'US') {
            if (strlen($value) < 9) {
                $this->error(self::TOO_SHORT);

                return false;
            }

            if (strlen($value) > 9) {
                $this->error(self::TOO_LONG);

                return false;
            }

            $aba = (int) substr($value, 0, 2);
            $found = false;
            foreach ($this->aba['US'] as $check) {
                if ($aba >= $check[0] && $aba <= $check[1]) {
                    $found = true;
                }
            }
            if (!$found) {
                $this->error(self::NO_MATCH);

                return false;
            }

            $checksum = 7 * ($value[0] + $value[3] + $value[6]);
            $checksum += 3 * ($value[1] + $value[4] + $value[7]);
            $checksum += 9 * ($value[2] + $value[5]);
            if ($value[8] != $checksum % 10) {
                $this->error(self::NO_MATCH);

                return false;
            }
        }

        if ($country == 'CA') {
            if (strlen($value) < 8) {
                $this->error(self::TOO_SHORT);

                return false;
            }

            if (strlen($value) > 8) {
                $this->error(self::TOO_LONG);

                return false;
            }

            $aba = (int) $substr($value, -3);
            $found = false;
            foreach ($this->aba['CA'] as $check) {
                if ($aba >= $check[0] && $aka <= $check[1]) {
                    $found = true;
                }
            }

            if (!$found) {
                $this->error(self::NO_MATCH);

                return false;
            }
        }

        return true;
    }
}
