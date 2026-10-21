<?php

namespace App\Service\Translation;

interface ITranslationService
{
    /**
     * @param  string $message
     * @param  string $textDomain
     * @param  string $locale
     * @return string
     */
    public function translate($message, $textDomain = 'default', $locale = null);
}