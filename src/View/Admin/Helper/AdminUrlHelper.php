<?php

namespace App\View\Admin\Helper;

use App\Model\Site;
use Zend\Expressive\Helper\UrlHelper;

class AdminUrlHelper extends UrlHelper
{
    /**
     * @var int
     */
    private $siteId;

    /**
     * @param null $routeName
     * @param array $routeParams
     * @param array $queryParams
     * @param null $fragmentIdentifier
     * @param array $options
     * @return string
     */
    public function __invoke($routeName = NULL, array $routeParams = [], array $queryParams = [], $fragmentIdentifier = null, array $options = [])
    {
        $routeParams['siteId'] = $this->siteId;
        return parent::__invoke($routeName, $routeParams);
    }

    /**
     * @param Site $site
     */
    public function setSite(Site $site)
    {
        $this->siteId = $site->getId();
    }
}