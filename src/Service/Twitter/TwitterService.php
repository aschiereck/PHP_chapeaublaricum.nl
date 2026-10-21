<?php

namespace App\Service\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Model\Site;
use App\Repository\ISiteSettingRepository;
use App\Session\ISessionManager;

class TwitterService implements ITwitterService
{
    const OAUTH_TOKEN_SESSION_KEY = 'twitter_oauth_token';
    const OAUTH_TOKEN_SECRET_SESSION_KEY = 'twitter_oauth_token_secret';

    const OAUTH_TOKEN_SETTINGS_KEY = 'twitter_oauth_token';
    const OAUTH_TOKEN_SECRET_SETTINGS_KEY = 'twitter_oauth_token_secret';

    /**
     * @var ISessionManager
     */
    private $sessionManager;
    /**
     * @var ISiteSettingRepository
     */
    private $settingRepository;
    /**
     * @var string
     */
    private $consumerKey;
    /**
     * @var string
     */
    private $consumerSecret;
    /**
     * @var string
     */
    private $callBackUrl;

    /**
     * TwitterService constructor.
     * @param ISessionManager $sessionManager
     * @param ISiteSettingRepository $settingRepository
     * @param string $consumerKey
     * @param string $consumerSecret
     * @param string $callBackUrl
     */
    public function __construct(ISessionManager $sessionManager, ISiteSettingRepository $settingRepository, $consumerKey, $consumerSecret, $callBackUrl)
    {
        $this->sessionManager = $sessionManager;
        $this->settingRepository = $settingRepository;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->callBackUrl = $callBackUrl;
    }

    /**
     * @param string $evolvedSiteId
     * @param string $redirectUrl
     * @throws \Exception
     */
    public function getRequestToken($evolvedSiteId, $redirectUrl)
    {
        $state = array(
            'evolvedSiteId' => $evolvedSiteId,
            'redirectUrl' => $redirectUrl
        );

        $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
        $result = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->callBackUrl . '?state=' . urlencode(serialize($state))));

        if($connection->getLastHttpCode() == 200)
        {
            $oauthToken = $result['oauth_token'];
            $oauthSecret = $result['oauth_token_secret'];

            $this->sessionManager->set(self::OAUTH_TOKEN_SESSION_KEY, $oauthToken);
            $this->sessionManager->set(self::OAUTH_TOKEN_SECRET_SESSION_KEY, $oauthSecret);

            $url = $connection->url('oauth/authorize', ['oauth_token' => $oauthToken]);
            header('location: ' . $url);
            die;
        }
        else
        {
            throw new \Exception('Failed to retrieve request token');
        }

    }

    /**
     * @param Site $site
     */
    public function getAccessToken(Site $site)
    {
        $oauthToken = $this->sessionManager->get(self::OAUTH_TOKEN_SESSION_KEY);
        $oauthSecret = $this->sessionManager->get(self::OAUTH_TOKEN_SECRET_SESSION_KEY);

        $twitter = $this->__getClient($oauthToken, $oauthSecret);
        $accessToken = $twitter->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);

        $oAuthToken = $accessToken['oauth_token'];
        $oAuthTokenSecret = $accessToken['oauth_token_secret'];

        $this->settingRepository->setValue($site, self::OAUTH_TOKEN_SETTINGS_KEY, $oAuthToken);
        $this->settingRepository->setValue($site, self::OAUTH_TOKEN_SECRET_SETTINGS_KEY, $oAuthTokenSecret);
    }

    /**
     * @param Site $site
     * @return Profile
     */
    public function getProfile(Site $site)
    {
        $profile = null;
        $client = $this->getClient($site);
        if($client)
        {
            $response = $client->get('account/verify_credentials');

            $profile = new Profile();
            $profile->name = $response->name;
        }

        return $profile;
    }

    /**
     * @param Site $site
     * @param $message
     */
    public function publish(Site $site, $message)
    {
        $client = $this->getClient($site);
        if($client)
        {
            $client->post('statuses/update', array('status' => $message, 'wrap_links' => 1));
        }
    }

    /**
     * @param Site $site
     * @return void
     */
    public function clear(Site $site)
    {
        $this->settingRepository->clear($site, self::OAUTH_TOKEN_SETTINGS_KEY);
        $this->settingRepository->clear($site, self::OAUTH_TOKEN_SECRET_SETTINGS_KEY);
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return TwitterOAuth::API_VERSION;
    }

    /**
     * @param Site $site
     * @return mixed
     */
    private function getClient(Site $site)
    {
        $oAuthToken = $this->settingRepository->getValue($site, self::OAUTH_TOKEN_SETTINGS_KEY);
        $oAuthTokenSecret = $this->settingRepository->getValue($site, self::OAUTH_TOKEN_SECRET_SETTINGS_KEY);

        if($oAuthToken && $oAuthTokenSecret)
        {
            return $this->__getClient($oAuthToken, $oAuthTokenSecret);
        }
        else
        {
            return null;
        }
    }

    /**
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     * @return TwitterOAuth
     */
    private function __getClient($oauthToken, $oauthTokenSecret)
    {
        return new TwitterOAuth(
            $this->consumerKey,
            $this->consumerSecret,
            $oauthToken,
            $oauthTokenSecret
        );
    }

}