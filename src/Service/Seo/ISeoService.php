<?php
namespace App\Service\Seo;

use App\Model\Site;

interface ISeoService
{
    /**
     * @param Site $site
     * @return float|int
     */
    public function calculateGlobalSeoScore(Site $site);

    /**
     * @param Site $site
     * @param int $limit
     * @return \string[]
     */
    public function getTopFocusWords(Site $site, $limit = 6);
}