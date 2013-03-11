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

Todo
----

* Validator\VatId - Validator for VAT ID

ChangeLog
---------

* 2013-03-11: *BC Break* Updated RoutingTransitNumber to force putting in a country.
* 2013-03-07: Added Luhn validator for testing canada Social Identification Number (SIN).
* 2013-03-06: Added Post Code validator to check against a country field rather than locale.
