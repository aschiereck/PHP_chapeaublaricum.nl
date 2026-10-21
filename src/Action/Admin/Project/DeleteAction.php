<?php

namespace App\Action\Admin\Project;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\IProjectRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/project/delete/{id:\d+}';

    /**
     * @var IProjectRepository
     */
    private $projectRepository;

    /**
     * DeleteAction constructor.
     * @param IProjectRepository $projectRepository
     */
    public function __construct(IProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $project = $this->projectRepository->findById($id);
        if(!$project)
        {
            throw new NotFoundException();
        }

        $this->projectRepository->delete($project);

        $this->getFlash()->success('Project verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}