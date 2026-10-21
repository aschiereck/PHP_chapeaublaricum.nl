<?php

namespace App\Service\Translation;

use Zend\I18n\Translator\Translator;
use Zend\Validator\Translator\TranslatorInterface;

class TranslationService implements TranslatorInterface, ITranslationService
{
    /** @var Translator */
    private $_translator;

    /**
     * @param $locale
     */
    public function __construct($locale)
    {
        $this->_translator = new Translator();
        $this->_translator->setLocale($locale);

        $this->addTranslationFile($locale, 'app.php', 'phparray');
        $this->addTranslationFile($locale, 'Zend_Validate.php', 'phparray');
        $this->addTranslationFile($locale, 'admin.mo', 'gettext');
        $this->addTranslationFile($locale, 'frontend.mo', 'gettext');
    }

    /**
     * @param $locale
     * @param $file
     * @param $type
     */
    private function addTranslationFile($locale, $file, $type)
    {
        $path = 'data/locale/'.$locale.'/' . $file;
        if(is_file($path))
        {
            $this->_translator ->addTranslationFile(
                $type,
                $path,
                'default',
                $locale
            );
        }
    }

    /**
     * @param  string $message
     * @param  string $textDomain
     * @param  string $locale
     * @return string
     * @throws \Exception
     */
    public function translate($message, $textDomain = 'default', $locale = null)
    {
        if(!$this->_translator)
        {
            throw new \Exception('Locale not initialized');
        }
        return $this->_translator->translate($message, $textDomain, $locale);
    }
}