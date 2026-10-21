<?php

namespace App\View\Admin\Helper;

use App\Repository\IModuleRepository;
use App\Service\Translation\ITranslationService;
use App\View\Admin\Model\Menu;
use App\View\Admin\Model\MenuGroup;
use App\View\Admin\Model\MenuItem;

class AdminMenuHelper
{
    /**
     * @var AdminUrlHelper
     */
    private $_urlHelper;
    /**
     * @var IModuleRepository
     */
    private $moduleRepository;
    /**
     * @var ITranslationService
     */
    private $translationService;

    /**
     * AdminMenuHelper constructor.
     * @param IModuleRepository $moduleRepository
     * @param ITranslationService $translationService
     * @param AdminUrlHelper $urlHelper
     */
    public function __construct(
        IModuleRepository $moduleRepository,
        ITranslationService $translationService,
        AdminUrlHelper $urlHelper
    )
    {
        $this->_urlHelper = $urlHelper;
        $this->moduleRepository = $moduleRepository;
        $this->translationService = $translationService;
    }

    /**
     * @param Menu $menu
     * @return string
     */
    public function __invoke(Menu $menu)
    {
        $counter = 1;
        $html = '';

        foreach($menu->getMenu() as $menuItem)
        {
            if($menuItem instanceof MenuItem)
            {
                $html .= ' <div class="sidebar-left-nav-outer"><ul>';
                $html .= $this->renderItem($menuItem, $menu->getSite()->getId());
                $html .= '</ul></div>';
            }
            else if($menuItem instanceof MenuGroup)
            {
                $html .= $this->renderGroup($menuItem, $counter, $menu->getSite()->getId());
            }
            $counter++;
        }

        return $html;
    }

    /**
     * @param MenuGroup $group
     * @param $counter
     * @param $siteId
     * @return string
     */
    private function renderGroup(MenuGroup $group, $counter, $siteId)
    {
        $collapsed = ($group->containsActiveChild() ? '' : 'collapsed');
        $in = ($group->containsActiveChild() ? 'in' : '');

        $title = '
        <div class="title-block">
            <h3>'.$group->name.'
              <div class="icon-block">
                    <a role="button" data-toggle="collapse" href="#collapse-nav-'.$counter.'" aria-controls="collapse-nav-'.$counter.'" class="icon-arrow-down '.$collapsed .'"></a>
                </div>
            </h3>
        </div>';

        $childHtml = '
        <div id="collapse-nav-'.$counter.'" class="panel-collapse collapse '.$in.'" role="tabpanel">
            <div class="sidebar-left-nav-outer custom-sub">
                <ul>';

        foreach($group->menuItems as $item)
        {
            $childHtml .= $this->renderItem($item, $siteId);
        }

        $childHtml .= '</ul></div></div>';

        return '<div class="sidebar-left-nav-outer custom">'.$this->translationService->translate($title).$childHtml.'</div>';
    }

    /**
     * @param MenuItem $item
     * @param $siteId
     * @return string
     */
    private function renderItem(MenuItem $item, $siteId)
    {
        $html = '';

        if($this->moduleRepository->isEnabled($item->module))
        {
            $badge = '';
            if ($item->count)
            {
                $badge = '<span class="badge">' . $item->count . '</span>';
            }

            $classes = ['module-' . $item->module];
            if ($item->isActive)
            {
                $classes[] = 'active';
            }


            $route = $this->_urlHelper->__invoke($item->route, ['siteId' => $siteId]);

            $html = '<li class="' . implode(' ', $classes) . '"><a href="' . $route . '">' . $this->translationService->translate($item->label) . $badge . '</a></li>';
        }
        return $html;
    }
}