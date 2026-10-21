<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 5-4-2017
 * Time: 23:58
 */
namespace App\Service\Seo;

use App\Model\Project;

interface IProjectCompletenessChecker
{
    /**
     * @param Project $project
     * @return int
     */
    public function calculateCompletness(Project $project);
}