<?php

namespace App\Repository;

use App\Model\Project;
use App\Model\Site;

class ProjectRepository extends AbstractRepository implements IProjectRepository
{
    /**
     * @param Project $project
     */
    public function delete(Project $project)
    {
        $this->_em->remove($project);
        $this->_em->flush();
    }


    /**
     * @param Project $project
     */
    public function save(Project $project)
    {
        $this->_em->persist($project);
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return Project|object
     */
    public function findById($id)
    {
        return $this->_em->find(Project::class, $id);
    }

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site)
    {
        $projects = array();
        foreach($site->getProjects() as $project)
        {
            $projects[$project->getId()] = $project->getName();
        }
        return $projects;
    }

    /**
     * @return int
     */
    public function getMaxPosition()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('max(p.position)')
            ->from(Project::class, 'p');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Site $site
     * @param $projectUrl
     * @return Project
     */
    public function findByUrl(Site $site, $projectUrl)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('project')
            ->from(Project::class, 'project')
            ->andWhere($qb->expr()->eq('project.url', ':url'))
            ->andWhere($qb->expr()->eq('project.site', $site->getId()))
            ->setParameter('url', $projectUrl);

        return $qb->getQuery()->getOneOrNullResult();
    }
}