<?php

namespace App\Service\Evolved;

use App\Config\IEvolvedConfigProvider;
use App\Util\Http\IHttpClientFactory;
use App\Service\License\LicenseValidator;
use App\Session\ISessionManager;
use Exception;

class Api implements IApi
{
    /**
     * @var IHttpClientFactory
     */
    private $clientFactory;
    /**
     * @var LicenseValidator
     */
    private $licenseValidator;
    /**
     * @var
     */
    private $apiUrl;
    /**
     * @var ISessionManager
     */
    private $sessionManager;

    /**
     * Api constructor.
     * @param IHttpClientFactory $clientFactory
     * @param ISessionManager $sessionManager
     * @param LicenseValidator $licenseValidator
     * @param IEvolvedConfigProvider $configProvider
     */
    public function __construct(
        IHttpClientFactory $clientFactory,
        ISessionManager $sessionManager,
        LicenseValidator $licenseValidator,
        IEvolvedConfigProvider $configProvider
    )
    {
        $this->clientFactory = $clientFactory;
        $this->licenseValidator = $licenseValidator;
        $this->apiUrl = $configProvider->getEvolvedConfig()->getApiUrl();
        $this->sessionManager = $sessionManager;
    }

    /**
     * @param $host
     * @return bool
     * @throws Exception
     */
    public function verifyHost($host)
    {
        try
        {
            $response = $this->makeRequest('verifyHost' . '/' .  $this->getEvolvedId() . '/' . base64_encode($host));
            if (isset($response['isValid']))
            {
                return (bool)$response['isValid'];
            }
        }
        catch(Exception $e)
        {}

        return false;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function validateToken($token)
    {
        try
        {
            $response = $this->makeRequest('validateToken' . '/' . $this->getEvolvedId() . '/' . $token);
            if (isset($response['isValid']))
            {
                return (bool)$response['isValid'];
            }
        }
        catch(Exception $e)
        {}

        return false;
    }

    /**
     * @return BrandingProfile
     */
    public function getBranding()
    {
        if(!$this->sessionManager->exists('branding-request-cache'))
        {
            $responseData = $this->makeRequest('getBranding' . '/' . $this->getEvolvedId());
            $this->sessionManager->set('branding-request-cache', serialize($responseData));
        }

        $brandingRequestData = unserialize($this->sessionManager->get('branding-request-cache'));

        $cmsName = isset($brandingRequestData['cmsName']) ? $brandingRequestData['cmsName'] : '';
        $loginLogoUrl = isset($brandingRequestData['loginLogoUrl']) ? $brandingRequestData['loginLogoUrl'] : '';
        $cmsLogoUrl = isset($brandingRequestData['cmsLogoUrl']) ? $brandingRequestData['cmsLogoUrl'] : '';
        $primaryColor = isset($brandingRequestData['primaryColor']) ? $brandingRequestData['primaryColor'] : '';
        $secondaryColor = isset($brandingRequestData['secondaryColor']) ? $brandingRequestData['secondaryColor'] : '';
        $loginText = isset($brandingRequestData['loginText']) ? $brandingRequestData['loginText'] : '';
        $backgroundImageUrl = isset($brandingRequestData['backgroundImageUrl']) ? $brandingRequestData['backgroundImageUrl'] : '';

        $branding = new BrandingProfile(
            $cmsName,
            $loginLogoUrl,
            $cmsLogoUrl,
            $primaryColor,
            $secondaryColor,
            $loginText,
            $backgroundImageUrl
        );

        return $branding;
    }

    /**
     * @param string $url
     * @return array
     * @throws Exception
     */
    private function makeRequest($url)
    {
        try
        {
            $client = $this->clientFactory->createClient($this->apiUrl  . $url);
            $client->setTimeout(5);
            $response = $client->send();
            $data = json_decode($response->getContent(), true);
            return $data;
        }
        catch(Exception $e)
        {
            throw new Exception('Failed to connect to master');
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getEvolvedId()
    {
        $license = $this->licenseValidator->getLicense();
        if(!$license)
        {
            throw new Exception('Invalid license');
        }

        return $license['evolvedId'];
    }
}