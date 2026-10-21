<?php
namespace App\Service\Facebook;

use App\Model\Site;

interface IFacebookService
{
    /**
     * Part 1 in the Facebook OAuth process
     * @param string $evolvedSiteId
     * @param string $redirectUrl
     */
    public function fetchRequestToken($evolvedSiteId, $redirectUrl);

    /**
     * Part 2 of the Oauth process: retrieve an accessToken
     * @param Site $site
     * @return
     */
    public function getAccessToken(Site $site);

    /**
     * @param Site $site
     * @return UserProfile
     */
    public function getUserProfile(Site $site);

    /**
     * @param Site $site
     * @return Page[]
     */
    public function getPages(Site $site);

    /**
     * @param Site $site
     * @param $id
     * @return
     */
    public function setPublishId(Site $site, $id);

    /**
     * @param Site $site
     * @return string
     */
    public function getPublishId(Site $site);

    /**
     * @param Site $site
     * @return void
     */
    public function clear(Site $site);

    /**
     * @param Site $site
     * @param string $message
     * @param string $link
     * @return
     */
    public function publish(Site $site, $message, $link);

    /**
     * @return string
     */
    public function getVersion();
}