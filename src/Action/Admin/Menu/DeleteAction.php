<?php

namespace App\Action\Admin\Menu;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\IMenuItemRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/site/menu/{id:\d+}';

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
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $menuItem = $this->menuItemRepository->findById($id);
        if(!$menuItem)
        {
            throw new NotFoundException();
        }

        $this->menuItemRepository->delete($menuItem);

        $this->getFlash()->success('Menu item verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}