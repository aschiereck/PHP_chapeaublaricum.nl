<?php
namespace App\Repository;

use App\Model\Project;
use App\Model\Site;

interface IProjectRepository
{
    /**
     * @param Project $project
     */
    public function delete(Project $project);

    /**
     * @param Project $project
     */
    public function save(Project $project);

    /**
     * @param $id
     * @return Project
     */
    public function findById($id);

    /**
     * @return int
     */
    public function getMaxPosition();

    /**
     * @param Site $site
     * @param $projectUrl
     * @return Project
     */
    public function findByUrl(Site $site, $projectUrl);

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site);
}