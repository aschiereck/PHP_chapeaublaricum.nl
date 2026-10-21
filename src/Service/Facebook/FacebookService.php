<?php

namespace App\Service\Facebook;

use App\Model\Site;
use App\Repository\ISiteSettingRepository;
use App\Session\ISessionManager;
use Facebook\Facebook;

class FacebookService implements IFacebookService
{
    const GRAPH_VERSION = 'v2.8';
    const ACCESS_TOKEN_SETTINGS_KEY = 'facebook_access_token';
    const PUBLISH_ID = 'facebook_publish_id';
    const PUBLISH_TYPE = 'facebook_publish_type';

    /**
     * @var string
     */
    private $appId;
    /**
     * @var string
     */
    private $appSecret;
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
    private $callBackUrl;

    /**
     * GoogleAnalyticsService constructor.
     * @param ISessionManager $sessionManager
     * @param ISiteSettingRepository $settingRepository
     * @param string $appId
     * @param string $appSecret
     * @param string $callBackUrl
     * @internal param string $refreshToken
     */
    public function __construct(ISessionManager $sessionManager, ISiteSettingRepository $settingRepository, $appId, $appSecret, $callBackUrl)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->sessionManager = $sessionManager;
        $this->settingRepository = $settingRepository;
        $this->callBackUrl = $callBackUrl;
    }

    /**
     * Part 1 in the Facebook OAuth process
     * @param string $evolvedSiteId
     * @param string $redirectUrl
     */
    public function fetchRequestToken($evolvedSiteId, $redirectUrl)
    {
        $fb = $this->getClient(null);
        $helper = $fb->getRedirectLoginHelper();

        $state = array(
            'evolvedSiteId' => $evolvedSiteId,
            'redirectUrl' => $redirectUrl
        );

        $helper->getPersistentDataHandler()->set('state', urlencode(serialize($state)));
        $permissions = ['public_profile','email','publish_actions','manage_pages','publish_pages'];
        $loginUrl = $helper->getLoginUrl($this->callBackUrl, $permissions);

        header('location: ' . $loginUrl);
        die;
    }

    /**
     * Part 2 of the Oauth process: retrieve an accessToken
     * @param Site $site
     */
    public function getAccessToken(Site $site)
    {
        $fb = $this->getClient(null);
        $helper = $fb->getRedirectLoginHelper();

        $accessToken = $helper->getAccessToken($this->callBackUrl);
        $oAuth2Client = $fb->getOAuth2Client();
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->appId);
        // If you know the user ID this access token belongs to, you can validate it here
        $tokenMetadata->validateExpiration();

        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        $this->settingRepository->setValue($site, self::ACCESS_TOKEN_SETTINGS_KEY, $accessToken);

        //Set default publish method
        $profile = $this->getUserProfile($site);
        $this->setPublishId($site, $profile->id);
    }

    /**
     * @param Site $site
     * @return UserProfile
     */
    public function getUserProfile(Site $site)
    {
        $profile = null;
        $accessToken = $this->settingRepository->getValue($site, self::ACCESS_TOKEN_SETTINGS_KEY);
		
        if($accessToken)
        {
            $fb = $this->getClient($accessToken);
            $response = $fb->get('/me?fields=id,name');
            $user = $response->getGraphUser();

            $profile = new UserProfile();
            $profile->id = $user->getId();
            $profile->name = $user->getName();
        }

        return $profile;
    }

    /**
     * @param Site $site
     * @param $id
     */
    public function setPublishId(Site $site, $id)
    {
        $this->settingRepository->setValue($site, self::PUBLISH_ID, $id);
    }

    /**
     * @param Site $site
     * @return string
     */
    public function getPublishId(Site $site)
    {
        return $this->settingRepository->getValue($site, self::PUBLISH_ID);
    }

    /**
     * @param Site $site
     * @return Page[]
     */
    public function getPages(Site $site)
    {
        $pages = [];
        $accessToken = $this->settingRepository->getValue($site, self::ACCESS_TOKEN_SETTINGS_KEY);

        if($accessToken)
        {
            $fb = $this->getClient($accessToken);
            $response = $fb->get('me/accounts');
            $body = $response->getDecodedBody();

            if(isset($body['data']))
            {
                foreach($body['data'] as $account)
                {
                    if($account['category'] != Page::ACCOUNT_CATEGORY)
                    {
                        $page = new Page();
                        $page->id = $account['id'];
                        $page->name = $account['name'];
                        $page->accessToken = $account['access_token'];
                        $pages[] = $page;
                    }
                }
            }
        }

        return $pages;
    }

    /**
     * @param Site $site
     * @param string $message
     * @param string $link
     */
    public function publish(Site $site, $message, $link)
    {
        $accessToken = $this->settingRepository->getValue($site, self::ACCESS_TOKEN_SETTINGS_KEY);
        if($accessToken)
        {
            $profile = $this->getUserProfile($site);
            $publishId = $this->getPublishId($site);
            $fb = $this->getClient($accessToken);

            $publishAsPerson = $profile->id == $publishId;
            if($publishAsPerson)
            {
                $fb->post('me/feed', ['message'=>$message, 'link'=>$link]);
            }
            else
            {
                $fb->post($publishId . '/feed', ['message'=>$message, 'link'=>$link]);
            }
        }
    }

    /**
     * @param Site $site
     */
    public function clear(Site $site)
    {
        $this->settingRepository->clear($site, self::ACCESS_TOKEN_SETTINGS_KEY);
        $this->settingRepository->clear($site, self::PUBLISH_ID);
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return self::GRAPH_VERSION;
    }

    /**
     * @param $accessToken
     * @return Facebook
     */
    private function getClient($accessToken)
    {
        $fb = new Facebook([
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
            'default_graph_version' => self::GRAPH_VERSION,
        ]);

        if($accessToken)
        {
            $fb->setDefaultAccessToken($accessToken);
        }

        return $fb;
    }
}
