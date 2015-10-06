<?php
/**
 * MwCommon
 */

namespace MwCommon\Validator;

use Traversable;
use Zend\Soap\Client;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\AbstractValidator;

class VatIN extends AbstractValidator
{

    const NO_MATCH    = 'vatinNoMatch';
    const UNSUPPORTED = 'vatinUnsupported';
    const INVALID     = 'vatinInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NO_MATCH       => 'The input does not match the VATIN format',
        self::UNSUPPORTED    => 'The country provided is currently unsupported',
        self::INVALID        => 'Invalid type given.  String expected',
    );

    /**
     * VATIN formats.
     *
     * @see http://en.wikipedia.org/wiki/VAT_identification_number
     * @var array
     */
    protected $vatin = array(
        // EU countries
        'AT' => '/^ATU[\w]{8}$/',
        'BE' => '/^BE0[\d]{9}$/',
        'BG' => '/^BG[\d]{9,10}$/',
        'CY' => '/^CY[\w]{9}$/',
        'CZ' => '/^CZ[\d]{8,10}$/',
        'DE' => '/^DE[\d]{9}$/',
        'DK' => '/^DK[\d]{8}$/',
        'EE' => '/^EE[\d]{9}$/',
        'EL' => '/^EL[\d]{9}$/',
        'ES' => '/^ES[A-Z0-9][\d]{7}[A-Z0-9]$/',
        'FI' => '/^FI[\d]{8}$/',
        'FR' => '/^FR[\w]{2}[\d]{9}$/',
        'GB' => '/^GB([\d]{9,12}|(GD|HA)[\d]{3})$/',
        'HU' => '/^HU[\d]{8}$/',
        'IE' => '/^IE[\d][\w][\d]{5}[A-Za-z]$/',
        'IT' => '/^IT[\d]{11}$/',
        'LT' => '/^LT([\d]{9}|[\d]{12})$/',
        'LU' => '/^LU[\d]{8}$/',
        'LV' => '/^LV[\d]{11}$/',
        'MT' => '/^MT[\d]{8}$/',
        'NL' => '/^NL[\d]{9}B[\d]{2}$/',
        'PL' => '/^PL[\d]{10}$/',
        'PT' => '/^PT[\d]{9}$/',
        'RO' => '/^RO[\d]{2,10}$/',
        'SE' => '/^SE[\d]{10}01$/',
        'SI' => '/^SI[\d]{8}$/',
        'SK' => '/^SK[\d]{10}$/',
        // NON-EU Countries
        'AL' => '/^(AL)?[J|K][\d]{8}[A-Z]$/',
        'AU' => '/^(AU)?[\d]{9}$/',
        'BY' => '/^(BY)?[\d]{9}$/',
        'CA' => '/^(CA)?[\d]{9}$/',
        'CH' => '/^CHE([\d]{6}|[\d]{9})(IVA|MWST|TVA)$/',
        'HR' => '/^HR[\d]{11}$/',
        'NO' => '/^NO[\d]{9}MVA$/',
        'PH' => '/^(PH)?[\d]{12}$/',
        'RU' => '/^(RU)?([\d]{10}|[\d]{12})$/',
        'SM' => '/^(SM)?([\d]{5})$/',
        'TR' => '/^(TR)?[\d]{10}$/',
        'UA' => '/^(UA)?[\d]{12}$/',
        // LA Countries
        'AR' => '/^(AR)?[\d]{11}$/',
        'BO' => '/^(BO)?[\d]+$/', // no documentation on formatting rules
        'BR' => '/^(BR)?[\d]{14}$/',
        'CL' => '/^(CL)?[\d]{9}$/',
        'CO' => '/^(CO)?[\d]{10}$/',
        'CR' => '/^(CR)?[\d]+$/', // no documentation on formatting rules
        'DO' => '/^(DO)?[\d]+$/', // no documentation on formatting rules
        'EC' => '/^(EC)?[\d]{13}$/',
        'GT' => '/^(GT)?[\d]{8}$/',
        'HN' => '/^(HN)?[\d]+$/', // no documentation on formatting rules
        'MX' => '/^(MX)?[\d]{12}$/',
        'NI' => '/^(NI)?[\d]+$/', // no documentation on formatting rules
        'PA' => '/^(PA)?[\d]+$/', // no documentation on formatting rules
        'PY' => '/^(PY)?[\d]+$/', // no documentation on formatting rules
        'PE' => '/^(PE)?[\d]+$/', // no documentation on formatting rules
        'SV' => '/^(SV)?[\d]+$/', // no documentation on formatting rules
        'UY' => '/^(UY)?[\d]+$/', // no documentation on formatting rules
        'VE' => '/^(VE)?[E|G|J|V][\d]{9}$/',
    );

    /**
     * VIES VAT Validation
     * @var array
     */
    protected $viesCountries = array(
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'EL', 'ES', 'FI', 'FR', 'GB', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK',
    );

    /**
     * ISO 3611 Country Code
     *
     * @var string
     */
    protected $country;

    /**
     * Constructor for the Vatin validator
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

        $country = $this->getCountry();
        if (!isset($this->vatin[$country])) {
            if (isset($context[$country])) {
                $country = $context[$country];
            } else {
                $country = substr($value, 0, 2);
            }

            if (!isset($this->vatin[$country])) {
                $this->error(self::UNSUPPORTED);

                return false;
            }
        }

        if (!preg_match($this->vatin[$country], $value)) {
            $this->error(self::NO_MATCH);

            return false;
        }

        if (in_array($country, $this->viesCountries)) {
            $client = new Client('http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl', array(
                'soap_version' => SOAP_1_1,
            ));

            $vatNumber = $value;
            if (strpos($vatNumber, $country) === 0) {
                $vatNumber = substr($vatNumber, strlen($country));
            }

            try {
                $response = $client->checkVat(array(
                    'countryCode' => $country,
                    'vatNumber' => $vatNumber,
                ));
                if (!$response->valid) {
                    $this->error(self::NO_MATCH);

                    return false;
                }
            } catch (\SoapFault $e) {
                $this->error(self::NO_MATCH);

                return false;
            }
        }

        return true;
    }
}
