<?php

namespace App\View\Frontend\Helper;

use DateTime;
use Zend\View\Helper\AbstractHelper;

class SitemapHelper extends AbstractHelper
{
    /**
     * @param string $url
     * @param DateTime $modified
     * @return string
     */
    public function __invoke($url, $modified)
    {
        $xml = '<url>';
        $xml .= '<loc>'.$url.'</loc>';
        $xml .= '<lastmod>'.  $modified->format('Y-m-d\TH:i:s').'+01:00</lastmod>';
        $xml .= '</url>';

        return $xml;
    }
}