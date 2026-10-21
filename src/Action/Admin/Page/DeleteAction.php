<?php

namespace App\Action\Admin\Page;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\IPageRepository;
use App\Repository\IUserRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/page/delete/{id:\d+}';

    /**
     * @var IUserRepository
     */
    private $pageRepository;

    /**
     * @param IPageRepository $pageRepository
     */
    public function __construct(IPageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $page = $this->pageRepository->findById($id);
        if(!$page)
        {
            throw new NotFoundException();
        }

        $this->pageRepository->delete($page);

        $this->getFlash()->success('Pagina verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}