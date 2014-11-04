<?php
/**
 * MwCommon
 */

namespace MwCommon\Filter;

use Zend\Filter\AbstractFilter;

/**
 * ParagraphToHtml Filter
 */
class ParagraphToHtml extends AbstractFilter
{
    /**
     * Convert paragraphs of text into filtered HTML.
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        // Strip unicode bombs, and make sure all newlines are UNIX
        // newlines.
        $value = preg_replace('{^\xEF\xBB\xBF|\x1A}', '', $value);
        $text = preg_replace('{\r\n?}', "\n", $value);

        $lambda = function ($text) {
            $text = htmlspecialchars($text, ENT_COMPAT,  'UTF-8');
            $text = preg_replace('/--/', '&mdash;', $text);
            $text = nl2br($text, false);
            $text = "<p>$text</p>";

            return $text;
        };

        $paragraphs = preg_split('/\n{2,}/', $value, -1, PREG_SPLIT_NO_EMPTY);
        $paragraphs = array_map($lambda, $paragraphs);

        return implode("\n\n", $paragraphs);
    }
}
