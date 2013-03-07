MwCommon Module
===============

This module is made up of several various components to extend and enhance
Zend Framework 2.  Many of the items that will be in this module will likely
be submitted for inclusion into the ZF2 core.

Components
----------

* Filter\ParagraphToHtml - Takes input and converts PlainText to HTML
* Validator\Country - Interfaces with ISO3611-2.
* Validator\CountryRegion - Interfaces with ISO3611-2.
* Validator\Ein - Validator for US EIN numbers
* Validator\Iban - IBAN / BBAN validator for International Bank Accounts.
* Validator\Luhn - Luhn Algorithm validator; used for validating Canada SIN and some credit cards.
* Validator\PhoneNumber - Phone number validator based off XML data from libPhoneNumber (http://code.google.com/p/libphonenumber/)
* Validator\RoutingTransitNumber - Validator for US routing transit numbers
* Validator\Ssn - Validator for US SSN numbers

Todo
----

* Validator\RoutingTransitNumber - Extend validation to contain Canadian routing numbers
* Validator\VatId - Validator for VAT ID
