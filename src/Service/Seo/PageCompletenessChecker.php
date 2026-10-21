<?php

namespace App\Service\Seo;

use App\Model\Page;
use App\Model\PageBlock\PageBlockCustomTemplate;

class PageCompletenessChecker extends AbstractCompletenessChecker implements IPageCompletenessChecker
{
    /**
     * @param Page $page
     * @return int
     */
    public function calculateCompletness(Page $page)
    {
        $searchStrings = [
            $page->getName(),
            $page->getHeadTitle(),
        ];

        if($page->allowSeoIndex())
        {
            $searchStrings[] = $page->getMetaDescription();
        }

        /** @var PageBlockCustomTemplate $pageBlock */
        foreach($page->getPageBlocks() as $pageBlock)
        {
            if(get_class($pageBlock) == PageBlockCustomTemplate::class)
            {
                if($pageBlock->getContent())
                {
                    $searchStrings[] = $pageBlock->getContent();
                }
            }
        }

        return $this->calculateSeoCompletness($page->getFocusWordsList(), $searchStrings);
    }
}