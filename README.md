MwCommon Module
===============

This module is made up of several various components to extend and enhance
Zend Framework 2.  Many of the items that will be in this module will likely
be submitted for inclusion into the ZF2 core.

Components
----------

* Filter\ParagraphToHtml - Takes input and converts PlainText to HTML
* Validator\Country - Interfaces with ISO3611-1.
* Validator\CountryRegion - Interfaces with ISO3611-2.
* Validator\Ein - Validator for US EIN numbers
* Validator\Iban - IBAN / BBAN validator for International Bank Accounts.
* Validator\Luhn - Luhn Algorithm validator; used for validating Canada SIN and some credit cards.
* Validator\PhoneNumber - Phone number validator based off XML data from libPhoneNumber (http://code.google.com/p/libphonenumber/)
* Validator\PostCode - Extends Zend\I18n\Validator\PostCode to interface with ISO3611-1.
* Validator\RoutingTransitNumber - Validator for routing transit numbers (US & CA) - you must pass an option 'country'
* Validator\Ssn - Validator for US SSN numbers
* Validator\Swift - Simple validator for SWIFT-BIC codes; optional country matching.
* Validator\VatIN - Supports most countries; however, true verification is done on EU countries.

Notes
-----
* Most validators simply check the filtered out digits; in such cases using a filter to remove whitespace, common place formatting and other various characters is needed.

Todo
----

* Validator\Country - Move countries to a country DB class which supplies the data and is more reusable.
* Validator\CountryRegion - Move regions to a region DB class which supplies the data and is more reusable.
* Validator\Iban - Check against ZF2 Iban validator; see if there is usefulness to this over ZF2 provided.
* Validator\Swift - See if I can't get a download of the Swift BIC online directory to be able to utilize.
* Validator\VatIN - Provide options to skip VIES, cache WSDL, look up additional country rules which are currently missing.  Offer better handling of WSDL failures and configurability.

ChangeLog
---------

* 2013-03-14: Added a basic swift-bic validator.
* 2013-03-11: VatIN validator completed, added in missing RoutingTransitOption from constructor.
* 2013-03-11: *BC Break* Updated RoutingTransitNumber to force putting in a country.
* 2013-03-07: Added Luhn validator for testing canada Social Identification Number (SIN).
* 2013-03-06: Added Post Code validator to check against a country field rather than locale.
