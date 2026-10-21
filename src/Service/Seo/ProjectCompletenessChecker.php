<?php

namespace App\Service\Seo;

use App\Model\Project;

class ProjectCompletenessChecker extends AbstractCompletenessChecker implements IProjectCompletenessChecker
{
    /**
     * @param Project $project
     * @return int
     */
    public function calculateCompletness(Project $project)
    {
        $searchStrings = [
            $project->getName(),
            $project->getHeadTitle(),
            $project->getMetaDescription(),
            $project->getContent()
        ];

        return $this->calculateSeoCompletness($project->getFocusWordsList(), $searchStrings);
    }
}