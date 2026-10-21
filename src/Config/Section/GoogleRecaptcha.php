<?php

namespace App\Config\Section;

class GoogleRecaptcha
{
    /**
     * @var string
     */
    private $siteKey;
    /**
     * @var string
     */
    private $secret;

    /**
     * GoogleRecaptcha constructor.
     * @param string $siteKey
     * @param string $secret
     */
    public function __construct(string $siteKey, string $secret)
    {
        $this->siteKey = $siteKey;
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getSiteKey(): string
    {
        return $this->siteKey;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @return bool
     */
    public function isRecaptchaEnabled() : bool
    {
        return $this->getSiteKey() && $this->getSecret();
    }
}