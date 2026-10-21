<?php
namespace App\Service\GoogleAnalytics;

use App\Model\Site;
use Exception;

interface IGoogleAnalyticsService
{
    /**
     * @param Site $site
     * @return Profile[]
     */
    public function getProfiles(Site $site);

    /**
     * @param Site $site
     * @return User
     */
    public function getUserProfile(Site $site);

    /**
     * Get the visit data for the last 30 days
     * @param Site $site
     * @param $selectedProfileId
     * @return GraphData
     */
    public function getGraphData(Site $site, $selectedProfileId);

    /**
     * Clear all access tokens
     * @param Site $site
     * @return
     */
    public function clearData(Site $site);

    /**
     * @return string
     */
    public function getVersion();

    /**
     * Step 1 of the OAuth setup process
     * Make a request to Google to obtain an oauth token
     * @param string $evolvedSiteId
     * @param string $redirectUrl
     */
    public function makeAuthRequest($evolvedSiteId, $redirectUrl);

    /**
     * Step 2 of the OAuth setup process
     * Check if there is a code parameter and use it to obtain the refresh token and the access token
     * @param string $code
     * @return string
     * @throws Exception
     */
    public function exchangeCodeForRefreshToken($code);
}