<?php

namespace App\Config;

use App\Config\Section\Evolved;
use App\Config\Section\GoogleRecaptcha;

class AppConfig implements IEvolvedConfigProvider, IGoogleRecaptchaConfigProvider
{
    private $config;

    /**
     * AppConfig constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getEvolvedConfig() : Evolved
    {
        return new Evolved(
            $this->config['evolved']['version'],
            $this->config['evolved']['api']
        );
    }

    public function getGoogleRecaptchaConfig() : GoogleRecaptcha
    {
        return new GoogleRecaptcha(
            $this->config['google']['recaptcha']['site_key'] ?? '',
            $this->config['google']['recaptcha']['secret'] ?? ''
        );
    }
}