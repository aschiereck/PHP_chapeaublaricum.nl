<?php

namespace App\Action\Admin\Menu;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Repository\IMenuItemRepository;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/menu/';

    /**
     * @var IMenuItemRepository
     */
    private $menuItemRepository;

    /**
     * @param IMenuItemRepository $menuItemRepository
     */
    public function __construct(IMenuItemRepository $menuItemRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
    }

    /**
     * @return array
     */
    public function execute()
    {
        return [
            'menuItems' => $this->menuItemRepository->findRootNodes($this->getSite()),
        ];
    }
}