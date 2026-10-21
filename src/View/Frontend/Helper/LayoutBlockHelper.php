<?php

namespace App\View\Frontend\Helper;

use App\Model\Site;
use App\Repository\ILayoutTextBlockRepository;
use Zend\View\Helper\AbstractHelper;

class LayoutBlockHelper extends AbstractHelper
{
    /**
     * @var ILayoutTextBlockRepository
     */
    private $layoutTextBlockRepository;
    /**
     * @var Site
     */
    private $currentSite;

    /**
     * LayoutBlockHelper constructor.
     * @param ILayoutTextBlockRepository $layoutTextBlockRepository
     * @param Site $currentSite
     */
    public function __construct(ILayoutTextBlockRepository $layoutTextBlockRepository, Site $currentSite)
    {
        $this->layoutTextBlockRepository = $layoutTextBlockRepository;
        $this->currentSite = $currentSite;
    }

    /**
     * @param $tag
     * @return string
     */
    public function getBlockText(string $tag)
    {
        $block = $this->layoutTextBlockRepository->findByTag($this->currentSite, $tag);
        if(!$block)
        {
            return  '{{ No block with tag: "' . $tag . '" }}';
        }
        else
        {
            return $block->getContent();
        }
    }
}