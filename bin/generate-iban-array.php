<?php
/**
 * NOTE: This file will generate a mapping from the current file located on the swift registry
 * unfortunately there are some countries that are missing from the document that will have to
 * be manually modified.  Also some automated logic will make some mistakes on numbers and digits
 * that have to be manually corrected.
 */



/**
 * To Regular Expression
 *
 * @param string $struct
 * @param array $countries
 * @return string
 */
function toRegex($struct, array $countries = null) {
    if ($struct == 'Not in use') {
        return '';
    }
    // parse out patterns
    preg_match_all('/(\d+)(\!)?([nace])/', $struct, $matches);
    $types = array(
        'n' => '[0-9]',
        'a' => '[A-Z]',
        'c' => '[A-Za-z0-9]',
        'e' => '[\ ]',
    );

    $replace = array();
    foreach ($matches[0] as $k => $v) {
        $replace[$v] = '(%s){%s}';
        $pattern = $types[$matches[3][$k]];
        $length = $matches[1][$k];
        if ($matches[2][$k] != '!') {
            $length = ',' . $length;
        }
        $replace[$v] = sprintf('(%s{%s})', $pattern, $length);
    }

    $replace = str_replace(array_keys($replace), $replace, $struct);
    if (is_array($countries)) {
        $country = substr($replace, 0, 2);
        $replace = substr($replace, 2);
        $replace = '(' . implode('|', $countries) . ')' . $replace;
    }

    return '/^' . $replace . '$/';
}

/**
 * Fix Country Code
 * Parse out Country Code and SEPA county to be able to provide proper regex validation.
 * NOTE: Ordering is very important here, as the first country is always the example.
 *
 * @return array
 */
function fixCountryCode($countryCode, $sepaCounty) {
    $countryCode = str_replace(array('BIC', 'IBAN'), array('bic', 'iban'), $countryCode); // normalize string
    preg_match_all('/([A-Z]{2})/', $countryCode, $matches);
    $countryCode = array_unique($matches[0]);
    preg_match_all('/([A-Z]{2})/', $sepaCounty, $matches);
    $matches[0] = array_unique($matches[0]);
    $countryCode = array_merge($countryCode, $matches[0]);
    return $countryCode;
}

$iban = array();

$fp = fopen('http://www.swift.com/dsp/resources/documents/IBAN_Registry.txt', 'r');
while($row = fgetcsv($fp, 0, "\t")) {
    if (!isset($head)) {
        $head = true;
        continue;
    }
    $row[1] = fixCountryCode($row[1], $row[15]);

    $row[11] = explode(',', $row[11]); // multiple iban numbers but by country so take the first :)
    $row[11] = array_shift($row[11]);

    $row[4] = trim($row[4]);
    $row[4] = str_replace(array(':', ' '), '', $row[4]);
    $row[11] = trim($row[11]);
    $row[11] = str_replace(array(':', ' '), '', $row[11]);


    $iban[$row[1][0]] = array(
        'bban' => toRegex($row[4]),
        'iban' => toRegex($row[11], $row[1]),
    );
}

ksort($iban);

echo 'protected $iban = array(' . PHP_EOL;
foreach ($iban as $country => $values) {
    echo "\t'$country' => array(" . PHP_EOL;
    echo "\t\t'bban' => '{$values['bban']}'," . PHP_EOL;
    echo "\t\t'iban' => '{$values['iban']}'," . PHP_EOL;
    echo "\t)," . PHP_EOL;
}
echo ');';
