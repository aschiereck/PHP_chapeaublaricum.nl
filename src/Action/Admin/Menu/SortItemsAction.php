<?php

namespace App\Action\Admin\Menu;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\IMenuItemRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\EmptyResponse;

class SortItemsAction extends AbstractAdminAction
{
    const ROUTE = '/menu/sortItems/';

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
     * @throws \Exception
     */
    protected function execute()
    {
        $newPositions = $this->getPostParameter('position');
        $menuItemIds = array_map('intval', explode(',', $newPositions));

        $counter = 1;
        foreach($menuItemIds as $menuItemId)
        {
            $menuItem = $this->menuItemRepository->findById($menuItemId);
            if($menuItem)
            {
                $menuItem->setPosition($counter);
                $this->menuItemRepository->save($menuItem);
            }
            else
            {
                throw new \Exception('Unknown menuItem');
            }

            $counter++;
        }

        return new EmptyResponse();
    }
}