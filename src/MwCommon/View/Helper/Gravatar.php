<?php
/**
 * MwCommon
 */

namespace MwCommon\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Gravatar View Helper
 */
class Gravatar extends AbstractHelper
{
    /**
     * @var string
     */
    protected $url = 'https://secure.gravatar.com/avatar/%s?%s';

    /**
     * @var array
     */
    protected $defaults = array(
        's' => '40',
        'd' => 'mm',
        'r' => 'pg',
    );

    /**
     * Hash the email
     *
     * @param string $email
     * @return string
     */
    protected function hash($email)
    {
        $email = trim($email);
        $email = strtolower($email);
        return md5($email);
    }

    /**
     * Returns the URL
     *
     * @param string $email
     * @param array $options
     *
     * @return string
     */
    public function __invoke($email, array $options = array())
    {
        return sprintf(
            $url,
            $this->hash($email),
            http_build_query(array_merge($this->defaults, $options), '', '&amp;')
        );
    }
}
