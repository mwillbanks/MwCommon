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
* Validator\Iban - Simple regex validator for SWIFT codes
* Validator\RoutingTransitNumber - Validator for US routing transit numbers
* View\Helper\Gravatar - a simple gravatar view helper

Todo
----

* UNIT TESTS - yes should be doing these first but don't feel like it at the moment :)
* Validator\PhoneNumber - fork or based off of libPhoneNumber (http://code.google.com/p/libphonenumber/)
* Validator\Iban - extend validation and fork or base off of php-iban (http://code.google.com/p/php-iban/)
* View\Helper\Gravatar - make it more extensive to support profiles and a more simplistic API
