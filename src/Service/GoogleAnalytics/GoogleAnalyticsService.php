<?php

namespace App\Service\GoogleAnalytics;

use App\Model\Site;
use App\Repository\ISiteSettingRepository;
use App\Session\ISessionManager;
use Exception;
use Google_Client;
use Google_Service_Analytics;
use Google_Service_Oauth2;
use \DateTime;

class GoogleAnalyticsService implements IGoogleAnalyticsService
{
    const REFRESH_TOKEN_SETTINGS_KEY = 'google_analytics_refreshtoken';
    const PROFILE_ID_SETTINGS_KEY = 'google_analytics_profile_id';
    const TRACKING_CODE_SETTINGS_KEY = 'google_analytics_tracking_code';
    const ACCESS_TOKEN_SESSION_KEY = 'google_ananlytics_accesstoken';

    /**
     * @var string
     */
    private $clientKey;
    /**
     * @var string
     */
    private $clientSecret;
    /**
     * @var ISessionManager
     */
    private $sessionManager;
    /**
     * @var Google_Client
     */
    private $client;
    /**
     * @var ISiteSettingRepository
     */
    private $settingRepository;
    /**
     * @var string
     */
    private $callBackUrl;

    /**
     * GoogleAnalyticsService constructor.
     * @param ISessionManager $sessionManager
     * @param ISiteSettingRepository $settingRepository
     * @param string $clientKey
     * @param string $clientSecret
     * @param string $callBackUrl
     * @internal param string $refreshToken
     */
    public function __construct(ISessionManager $sessionManager, ISiteSettingRepository $settingRepository, $clientKey, $clientSecret, $callBackUrl)
    {
        $this->clientKey = $clientKey;
        $this->clientSecret = $clientSecret;
        $this->sessionManager = $sessionManager;
        $this->settingRepository = $settingRepository;
        $this->callBackUrl = $callBackUrl;
    }

    /**
     * @param Site $site
     * @return Profile[]
     */
    public function getProfiles(Site $site)
    {
        $profiles = [];
        $refreshToken = $this->getRefreshToken($site);

        if($refreshToken)
        {
            $client = $this->getClient($refreshToken);
            $analytics = new Google_Service_Analytics($client);

            $accounts = $analytics->management_profiles->listManagementProfiles('~all', '~all')->getItems();

            foreach ($accounts as $account)
            {
                $profile = new Profile();
                $profile->id = $account->id;
                $profile->trackingCode = $account->webPropertyId;
                $profile->name = $account->name;
                $profile->websiteUrl = $account->websiteUrl;
                $profiles[] = $profile;
            }
        }

        return $profiles;
    }

    /**
     * @param Site $site
     * @return User
     */
    public function getUserProfile(Site $site)
    {
        $user = null;
        $refreshToken = $this->getRefreshToken($site);

        if($refreshToken)
        {
            $client = $this->getClient($refreshToken);
            $oauth = new Google_Service_Oauth2($client);
            $me = $oauth->userinfo_v2_me->get();

            $user = new User();
            $user->name = $me->name;
            $user->imageUrl = $me->picture;
        }

         return $user;
    }

    /**
     * Get the visit data for the last 30 days
     * @param Site $site
     * @param $selectedProfileId
     * @return GraphData
     */
    public function getGraphData(Site $site, $selectedProfileId)
    {
        $result = null;
        $refreshToken = $this->getRefreshToken($site);

        if($refreshToken)
        {
            $client = $this->getClient($refreshToken);
            $analytics = new Google_Service_Analytics($client);

            $metrics = 'ga:pageviews,ga:visits';
            $startDate = date("Y-m-d", strtotime("-1 month"));
            $endDate = date("Y-m-d", strtotime("-1 day"));

            $optParams = array(
                'dimensions' => 'ga:date',
                'sort' => 'ga:date',
                'start-index' => 1,
                'max-results' => 10000
            );

            $report = $analytics->data_ga->get(
                'ga:' . $selectedProfileId,
                $startDate,
                $endDate,
                $metrics,
                $optParams
            );

            $total_page_views = $report->totalsForAllResults['ga:pageviews'];
            $total_visits = $report->totalsForAllResults['ga:visits'];
            $days = array();

            foreach ($report->rows as $row)
            {
                //$pageViews = $row[1];
                $date = $row[0];
                $visits = $row[2];

                $day = new DayStatistics();
                $day->date = DateTime::createFromFormat('Ymd', $date)->format('d-m-Y');
                $day->visits = $visits;

                $days[] = $day;
            }

            $profileInfo = $report->getProfileInfo();

            //Create output
            $result = new GraphData();
            $result->totalPageViews = $total_page_views;
            $result->totalVisits = $total_visits;
            $result->visitsPerDay = $days;
            $result->trackingCode  = $profileInfo['webPropertyId'];
            $result->profileName  = $profileInfo['profileName'];
        }

        return $result;
    }


    /**
     * Clear all access tokens
     * @param Site $site
     */
    public function clearData(Site $site)
    {
        $refreshToken = $this->getRefreshToken($site);
        if($refreshToken)
        {
            $client = $this->getClient($refreshToken);
            $client->revokeToken($refreshToken);
        }

        $this->settingRepository->clear($site, self::REFRESH_TOKEN_SETTINGS_KEY);
        $this->settingRepository->clear($site, self::PROFILE_ID_SETTINGS_KEY);
        $this->settingRepository->clear($site, self::TRACKING_CODE_SETTINGS_KEY);
        $this->sessionManager->set(self::ACCESS_TOKEN_SESSION_KEY, null);
    }

    /**
     * Step 1 of the OAuth setup process
     * Make a request to Google to obtain an oauth token
     * @param string $evolvedSiteId
     * @param string $redirectUrl
     */
    public function makeAuthRequest($evolvedSiteId, $redirectUrl)
    {
        $client = $this->getClient(null);

        //Set the real redirect in the state parameter
        $state = array(
            'evolvedSiteId' => $evolvedSiteId,
            'redirectUrl' => $redirectUrl
        );
        $client->setState(urlencode(serialize($state)));

        //Make request to the auth url
        $authUrl = $client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit();
    }

    /**
     * Step 2 of the OAuth setup process
     * Check if there is a code parameter and use it to obtain the refresh token and the access token
     * @param string $code
     * @return string
     * @throws Exception
     */
    public function exchangeCodeForRefreshToken($code)
    {
        $client = $this->getClient(null);
        $result = $client->fetchAccessTokenWithAuthCode($code);

        if(isset($result['refresh_token']))
        {
            return $result['refresh_token'];
        }
        /*
         * Code was already send but was lost. App needs to be removed from console.
         *  See: http://stackoverflow.com/questions/8942340/get-refresh-token-google-api
         * for details
         */
        else if(isset($result['access_token']))
        {
            throw new Exception('Refresh token was not send by Oauth server.');
        }
        else
        {
            throw new Exception('Unexpected answer: ' . var_export($result, true));
        }
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        $client = new Google_Client();
        $analytics = new Google_Service_Analytics($client);
        return $analytics->version;
    }


    /**
     * @param string $refreshToken
     * @return Google_Client
     */
    private function getClient($refreshToken)
    {
        if(!$this->client)
        {
            $client = new Google_Client();
            $client->setClientId($this->clientKey);
            $client->setClientSecret($this->clientSecret);
            $client->setRedirectUri($this->callBackUrl);

            //Set scopes and access type
            $client->setScopes(array(
                'https://www.googleapis.com/auth/analytics.readonly',
                'https://www.googleapis.com/auth/userinfo.profile'
            ));
            $client->setAccessType('offline');
            $client->setApprovalPrompt("force");

            //Check if there is an access token
            $accessToken = $this->sessionManager->get(self::ACCESS_TOKEN_SESSION_KEY);
            if($accessToken)
            {
                $client->setAccessToken($accessToken);
            }

            //If there is no access token, then use the refresh token to get one
            if ($client->isAccessTokenExpired() && $refreshToken)
            {
                //Use the token to get a new access token
                $client->refreshToken($refreshToken);
                $newAccessToken = $client->getAccessToken();

                //Save the token in the session so we don't have to fetch a new one
                $this->sessionManager->set(self::ACCESS_TOKEN_SESSION_KEY, $newAccessToken);
            }
            $this->client = $client;
        }

        return $this->client;
    }

    /**
     * @param Site $site
     * @return string
     */
    private function getRefreshToken(Site $site)
    {
        $refreshToken = $this->settingRepository->getValue($site, self::REFRESH_TOKEN_SETTINGS_KEY);
        return $refreshToken;
    }
}
