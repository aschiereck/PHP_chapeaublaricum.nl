<?php

namespace App\View\Frontend\Helper;

use App\Model\Link\PageLink;
use App\Model\MenuItem;
use App\Model\Page;
use Zend\View\Helper\AbstractHelper;

class MenuHelper extends AbstractHelper
{
    /**
     * @var LinkBuilder
     */
    private $linkBuilder;

    /**
     * MetaHelper constructor.
     * @param LinkBuilder $linkBuilder
     */
    public function __construct(LinkBuilder $linkBuilder)
    {
        $this->linkBuilder = $linkBuilder;
    }

    /**
     * @param MenuItem $menuItem
     * @return string
     */
    public function link(MenuItem $menuItem)
    {
        return $this->linkBuilder->link($menuItem->getLink());
    }

    /**
     * @param MenuItem $menuItem
     * @param Page $activePage
     * @return bool
     */
    public function isActive($menuItem, $activePage)
    {
        $isActive = false;

        if($menuItem->getLink())
        {
            if ($menuItem->getLink()->getType() == PageLink::TYPE)
            {
                /** @var PageLink $link */
                $link = $menuItem->getLink();
                $isActive = $link->getPage() && $activePage && $link->getPage()->getId() == $activePage->getId();
            }

            foreach ($menuItem->getChildren() as $child)
            {
                if (!$isActive && $this->isActive($child, $activePage))
                {
                    $isActive = true;
                }
            }
        }

        return $isActive;
    }
}