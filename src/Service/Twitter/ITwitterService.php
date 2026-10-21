<?php
namespace App\Service\Twitter;

use App\Model\Site;

interface ITwitterService
{
    /**
     * @param string $evolvedSiteId
     * @param string $redirectUrl
     * @throws \Exception
     */
    public function getRequestToken($evolvedSiteId, $redirectUrl);

    /**
     * @param Site $site
     * @return void
     */
    public function getAccessToken(Site $site);

    /**
     * @param Site $site
     * @return Profile
     */
    public function getProfile(Site $site);

    /**
     * @param Site $site
     * @param $message
     * @return
     */
    public function publish(Site $site, $message);

    /**
     * @param Site $site
     * @return void
     */
    public function clear(Site $site);

    /**
     * @return string
     */
    public function getVersion();
}