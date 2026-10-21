<?php

namespace App\Action\Admin\Project;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\IProjectRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\EmptyResponse;

class SortAction extends AbstractAdminAction
{
    const ROUTE = '/project/sort';

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
     * @throws \Exception
     */
    protected function execute()
    {
        $newPositions = $this->getPostParameter('position');
        $projectIds = array_map('intval', explode(',', $newPositions));
        $counter = 1;
        foreach($projectIds as $memberId)
        {
            $slide = $this->projectRepository->findById($memberId);
            if($slide)
            {
                $slide->setPosition($counter);
                $this->projectRepository->save($slide);
            }
            else
            {
                throw new \Exception('Unknown project');
            }

            $counter++;
        }

        return new EmptyResponse();
    }
}