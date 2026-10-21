<?php

namespace App\Action\Frontend\Project;

use App\Action\Frontend\AbstractFrontendTemplateAction;
use App\Exception\NotFoundException;
use App\Repository\IProjectRepository;

class ViewAction extends AbstractFrontendTemplateAction
{
    /**
     * @var IProjectRepository
     */
    private $projectRepository;

    /**
     * ViewAction constructor.
     * @param IProjectRepository $projectRepository
     */
    public function __construct(IProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @return array
     * @throws NotFoundException
     */
    public function execute()
    {
        $projectUrl = $this->getRouteParameter('projectSlug');
        $project = $this->projectRepository->findByUrl($this->getSite(), $projectUrl);
        if(!$project)
        {
            throw new NotFoundException();
        }

        $this->getViewModel()->headTitle = $project->getHeadTitle();
        $this->getViewModel()->metaDescription = $project->getMetaDescription();
        $this->getViewModel()->allowSeoIndex = true;
        $this->getViewModel()->activeArticle = $project;

        return [
            'project' => $project
        ];
    }
}