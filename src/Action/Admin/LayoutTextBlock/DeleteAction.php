<?php

namespace App\Action\Admin\LayoutTextBlock;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\ILayoutTextBlockRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/layoutTextBlock/delete/{id:\d+}';

    /**
     * @var ILayoutTextBlockRepository
     */
    private $layoutTextBlockRepository;

    /**
     * @param ILayoutTextBlockRepository $layoutTextBlockRepository
     */
    public function __construct(ILayoutTextBlockRepository $layoutTextBlockRepository)
    {
        $this->layoutTextBlockRepository = $layoutTextBlockRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $object = $this->layoutTextBlockRepository->findById($id);
        if(!$object)
        {
            throw new NotFoundException();
        }

        $this->layoutTextBlockRepository->delete($object);

        $this->getFlash()->success('Tekstblok verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}