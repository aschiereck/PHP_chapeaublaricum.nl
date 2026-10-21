<?php
namespace App\Repository;

use App\Model\Site;

interface ISiteSettingRepository
{
    /**
     * @param Site $site
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setValue(Site $site, $key, $value);

    /**
     * @param Site $site
     * @param $key
     * @return string
     */
    public function getValue(Site $site, $key);

    /**
     * @param Site $site
     * @param $key
     * @return void
     */
    public function clear(Site $site, $key);
}