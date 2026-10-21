<?php

namespace App\Action\Admin\Menu;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Model\MenuItem;
use App\Repository\IMenuItemRepository;
use App\Repository\IPageRepository;
use Psr\Http\Message\ResponseInterface as Response;

class EditAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/menu/edit/[/{id:\d+}]';

    /**
     * @var IMenuItemRepository
     */
    private $menuItemRepository;
    /**
     * @var IPageRepository
     */
    private $pageRepository;

    /**
     * @param IMenuItemRepository $menuItemRepository
     * @param IPageRepository $pageRepository
     */
    public function __construct(IMenuItemRepository $menuItemRepository, IPageRepository $pageRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $invalidFields = [];

        if($id)
        {
            $menuItem = $this->menuItemRepository->findById($id);
            if(!$menuItem)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $menuItem = new MenuItem();
            $menuItem = $menuItem->setSite($this->getSite());
            $menuItem->setPosition($this->menuItemRepository->getMaxPosition());
        }

        if($this->isPostRequest())
        {
            $this->setPostData($menuItem);

            if ($menuItem->isValid())
            {
                $this->menuItemRepository->save($menuItem);

                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(IndexAction::ROUTE);
            }
            else
            {
                $invalidFields = $menuItem->getValidationErrors();
            }
        }

        return [
            'menuItem' => $menuItem,
            'invalidFields' => $invalidFields,
            'pages' => $this->pageRepository->findList($this->getSite()),
            'rootMenuItems' => $this->menuItemRepository->findRootNodesList($this->getSite(), $menuItem)
        ];
    }

    /**
     * @param MenuItem $menuItem
     */
    private function setPostData(MenuItem $menuItem)
    {
        $menuItem->setName($this->getPostParameter('name'));

        $parent = null;
        if($this->getPostParameter('parent'))
        {
            $parent = $this->menuItemRepository->findById($this->getPostParameter('parent'));
        }
        $menuItem->setParent($parent);
        $menuItem->setLink($this->createLink($this->pageRepository));
    }
}