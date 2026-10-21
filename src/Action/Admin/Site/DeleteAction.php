<?php

namespace App\Action\Admin\Site;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\ISiteRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/site/delete/{id:\d+}';

    /**
     * @var ISiteRepository
     */
    private $siteRepository;

    /**
     * @param ISiteRepository $siteRepository
     */
    public function __construct(ISiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $site = $this->siteRepository->findById($id);
        if(!$site)
        {
            throw new NotFoundException();
        }

        $this->siteRepository->delete($site);

        $this->getFlash()->success('Site verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}