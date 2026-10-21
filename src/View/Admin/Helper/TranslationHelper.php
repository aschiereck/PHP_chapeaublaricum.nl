<?php

namespace App\View\Admin\Helper;

use App\Service\Translation\ITranslationService;
use Zend\View\Helper\AbstractHelper;

class TranslationHelper extends AbstractHelper
{
    /**
     * @var ITranslationService
     */
    private $translationService;

    /**
     * LayoutBlockHelper constructor.
     * @param ITranslationService $translationService
     */
    public function __construct(ITranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * @param $text
     * @return string
     */
    public function __invoke($text)
    {
        return $this->translationService->translate($text);
    }
}