<?php
/**
 * NOTE: This script will attempt to generate various data points from
 * libphonenumber (http://libphonenumber.googlecode.com) by parsing the
 * PhoneNumberMetaData.  This attempts to generate test data to check
 * against the phone number validator.
 */

/**
 * XML 2 Associative Array
 *
 * @param XMLReader $xml
 * @param string $name
 * @return array
 */
function xml2assoc($xml, $name)
{
    $tree = null;
    while ($xml->read()) {
        if ($xml->nodeType == XMLReader::END_ELEMENT) {
            return $tree;
        } elseif ($xml->nodeType == XMLReader::ELEMENT) {
            $node = array();

            $node['tag'] = $xml->name;

            if ($xml->hasAttributes) {
                $attributes = array();
                while ($xml->moveToNextAttribute()) {
                    $attributes[$xml->name] = $xml->value;
                }
                $node['attr'] = $attributes;
            }

            if (!$xml->isEmptyElement) {
                $childs = xml2assoc($xml, $node['tag']);
                $node['childs'] = $childs;
            }

            $tree[] = $node;
        } elseif ($xml->nodeType == XMLReader::TEXT) {
            $node = array();
            $node['text'] = $xml->value;
            $tree[] = $node;
        }
    }

    return $tree;
}

// download a local copy:
$fileName = '/tmp/phone-number-metadata.xml';
if (!file_exists($fileName)) {
    echo 'Downloading and storing local copy: ' . $fileName . PHP_EOL;
    file_put_contents($fileName, file_get_contents('http://libphonenumber.googlecode.com/svn/trunk/resources/PhoneNumberMetaData.xml'));
} else {
    echo 'Using local copy: ' . $fileName . PHP_EOL;
}

$xml = new XMLReader();
$xml->open($fileName);
$assoc = xml2assoc($xml, "root");
$xml->close();

$assoc = $assoc[0]['childs'][0]['childs'];

$tagMap = array(
    'generalDesc' => 'general',
    'fixedLine' => 'fixed',
    'tollFree' => 'tollfree',
    'premiumRate' => 'premium',
    'sharedCost' => 'shared',
    'personalNumber' => 'personal',
    'shortCode' => 'shortcode',
);

$tagSkip = array(
    'availableFormats',
    'areaCodeOptional',
    'noInternationalDialling',
);

$phone = array();
foreach ($assoc as $territory) {
    $country = $territory['attr']['id'];
    // we do not want Universal International Premium Rate Number
    if ($country == '001') {
        continue;
    }
    $phone[$country] = array(
        'code' => $territory['attr']['countryCode'],
        'patterns' => array(
            'exampleNumber' => array(),
        ),
    );
    // retrieve phone number patterns:
    $patterns = $territory['childs'];
    foreach ($patterns as $p) {
        if (in_array($p['tag'], $tagSkip)) {
            continue;
        }
        if (array_key_exists($p['tag'], $tagMap)) {
            $p['tag'] = $tagMap[$p['tag']];
        }
        foreach ($p['childs'] as $pattern) {
            if ($pattern['tag'] != 'exampleNumber') {
                continue;
            }
            foreach ($pattern['childs'] as $text) {
                if ($text['text'] == 'NA') {
                    continue;
                }
                $text['text'] = str_replace(array("\r\n", "\r", "\n", "\t", ' '), '', $text['text']);
                $phone[$country]['patterns'][$pattern['tag']][$p['tag']] = $text['text'];
            }
        }
    }
}

ksort($phone);

echo 'protected $phone = array(' . PHP_EOL;
foreach ($phone as $k => $v) {
    echo "\t'$k' => array(" . PHP_EOL;
    echo "\t\t'code' => '{$v['code']}'," . PHP_EOL;
    echo "\t\t'patterns' => array(" . PHP_EOL;
    echo "\t\t\t'example' => array(" . PHP_EOL;
    foreach ($v['patterns']['exampleNumber'] as $kk => $vv) {
        echo "\t\t\t\t'$kk' => '$vv'," . PHP_EOL;
    }
    echo "\t\t\t)," . PHP_EOL;
    echo "\t\t)," . PHP_EOL;
    echo "\t)," . PHP_EOL;
}
echo ');' . PHP_EOL;
