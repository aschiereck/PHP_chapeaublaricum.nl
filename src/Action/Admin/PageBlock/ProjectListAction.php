<?php

namespace App\Action\Admin\PageBlock;

use App\Model\PageBlock\PageBlockProjectList;
use App\Repository\IProjectRepository;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;

class ProjectListAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/project-list';

    /**
     * @var IProjectRepository
     */
    private $projectRepository;

    /**
     * NewsArchiveAction constructor.
     * @param IPageBlockSerializerService $pageBlockSerializerService
     * @param IProjectRepository $projectRepository
     */
    public function __construct(IPageBlockSerializerService $pageBlockSerializerService, IProjectRepository $projectRepository)
    {
        parent::__construct($pageBlockSerializerService);
        $this->projectRepository = $projectRepository;
    }

    /**
     * @return Response|array
     */
    protected function execute()
    {
        $invalidFields = [];

        $pageBlock = new PageBlockProjectList();

        if($this->isPostRequest())
        {
            $showAll = in_array($this->getPostParameter('showAll'), ['true', 1]);
            $pageBlock->setShowAllProjects($showAll);
            $selectedProjects = [];

            if(!$showAll)
            {
                $projectIds  = is_array($this->getPostParameter('projectIds')) ? $this->getPostParameter('projectIds') : [];
                foreach($projectIds as $projectId)
                {
                    $selectedProjects[] = $this->projectRepository->findById($projectId);
                }
            }

            $pageBlock->setProjects($selectedProjects);
        }

        if($this->isPostRequest() && !$this->isPageBuilderRequest())
        {
            if ($pageBlock->isValid())
            {
                return $this->createSuccessJson($pageBlock);
            }
            else
            {
                return $this->createFailedJson($pageBlock->getValidationErrors());
            }
        }

        return [
            'pageBlock' => $pageBlock,
            'invalidFields' => $invalidFields,
            'projects' => $this->getSortedProjectList($pageBlock->getSelectedProjectIds())
        ];
    }

    /**
     * Sort the projects so order is preserved when using the multi select control
     * @param int[] $selectedProjectIds
     * @return array
     */
    private function getSortedProjectList($selectedProjectIds)
    {
        $sortedProjects = [];
        $allProjects = $this->projectRepository->findList($this->getSite());

        foreach($selectedProjectIds as $selectedProjectId)
        {
            if(isset($allProjects[$selectedProjectId]))
            {
                $sortedProjects[$selectedProjectId] = $allProjects[$selectedProjectId];
                unset($allProjects[$selectedProjectId]);
            }
        }

        foreach($allProjects as $projectId => $projectName)
        {
            $sortedProjects[$projectId] = $projectName;
        }

        return $sortedProjects;
    }
}