<?php
namespace App\Config;

use App\Config\Section\GoogleRecaptcha;

interface IGoogleRecaptchaConfigProvider
{
    public function getGoogleRecaptchaConfig(): GoogleRecaptcha;
}