<?php

namespace App\Service\Evolved;

class BrandingProfile
{
    /**
     * @var string
     */
    private $cmsName;
    /**
     * @var string
     */
    private $loginLogoUrl;
    /**
     * @var string
     */
    private $primaryColor;
    /**
     * @var string
     */
    private $secondaryColor;
    /**
     * @var string
     */
    private $loginText;
    /**
     * @var string
     */
    private $backgroundImageUrl;
    /**
     * @var string
     */
    private $cmsLogoUrl;

    /**
     * Branding constructor.
     * @param string $cmsName
     * @param string $loginLogoUrl
     * @param string $cmsLogoUrl
     * @param string $primaryColor
     * @param string $secondaryColor
     * @param string $loginText
     * @param string $backgroundImageUrl
     */
    public function __construct($cmsName, $loginLogoUrl, $cmsLogoUrl, $primaryColor, $secondaryColor, $loginText, $backgroundImageUrl)
    {
        $this->cmsName = $cmsName;
        $this->loginLogoUrl = $loginLogoUrl;
        $this->primaryColor = $primaryColor;
        $this->secondaryColor = $secondaryColor;
        $this->loginText = $loginText;
        $this->backgroundImageUrl = $backgroundImageUrl;
        $this->cmsLogoUrl = $cmsLogoUrl;
    }

    /**
     * @return string
     */
    public function getPrimaryColor()
    {
        return $this->primaryColor;
    }

    /**
     * @return string
     */
    public function getSecondaryColor()
    {
        return $this->secondaryColor;
    }

    /**
     * @return string
     */
    public function getLoginText()
    {
        return $this->loginText;
    }

    /**
     * @return string
     */
    public function getBackgroundImageUrl()
    {
        return $this->backgroundImageUrl;
    }

    /**
     * @return string
     */
    public function getCmsName()
    {
        return $this->cmsName;
    }

    /**
     * @return string
     */
    public function getLoginLogoUrl()
    {
        return $this->loginLogoUrl;
    }

    /**
     * @return string
     */
    public function getCmsLogoUrl()
    {
        return $this->cmsLogoUrl;
    }
}