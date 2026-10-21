<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\PageBlockNewsArchive;
use App\Model\PageBlock\PageBlockProjectList;
use App\Repository\IProjectRepository;

class PageBlockProjectListSerializer implements IPageBlockSerializer
{
    /**
     * @var IProjectRepository
     */
    private $projectRepository;

    /**
     * PageBlockProjectListSerializer constructor.
     * @param IProjectRepository $projectRepository
     */
    public function __construct(IProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param PageBlockProjectList $pageBlock
     * @return string
     */
    public function toArray($pageBlock)
    {
        return [
            'showAll' => $pageBlock->isShowAllProjects(),
            'projectIds' => $pageBlock->getSelectedProjectIds()
        ];
    }

    /**
     * @param array $values
     * @param PageBlockNewsArchive $pageBlock
     * @return PageBlockNewsArchive $pageBlock
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockProjectList();

        $showAll = (bool)$values['showAll'];
        $pageBlock->setShowAllProjects($showAll);

        $selectedProjects = [];
        $projectIds = $values['projectIds'];
        foreach($projectIds as $projectId)
        {
            $project = $this->projectRepository->findById($projectId);
            if($project)
            {
                $selectedProjects[] = $project;
            }
        }

        $pageBlock->setProjects($selectedProjects);
        return $pageBlock;
    }
}
