<?php

namespace App\Model\PageBlock;

use App\Model\PageBlockProject;
use App\Model\Project;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockProjectList extends AbstractPageBlock
{
    const TEMPLATE = 'project-list';

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $showAllProjects = true;

    /**
     * @var PageBlockProject[]
     * @ORM\OneToMany(targetEntity="\App\Model\PageBlockProject", mappedBy="pageBlock", cascade={"remove", "persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $pageBlockProjects;

    /**
     * PageBlockCarousel constructor.
     */
    public function __construct()
    {
        $this->pageBlockProjects = new ArrayCollection();
        $this->setTemplate(self::TEMPLATE);
    }

    /**
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();
        return $invalidFields;
    }


    /***************************************************************************************
     * Getters / Setters
     ***************************************************************************************/

    /**
     * @return string
     */
    public function getEditorTitle()
    {
        return ($this->isShowAllProjects() ? 'alle producten' : 'selectie');
    }

    /**
     * @return bool
     */
    public function isShowAllProjects()
    {
        return $this->showAllProjects;
    }

    /**
     * @param bool $showAllProjects
     * @return PageBlockProjectList
     */
    public function setShowAllProjects($showAllProjects)
    {
        $this->showAllProjects = $showAllProjects;
        return $this;
    }

    /**
     * @return Project[]
     */
    public function getProjects()
    {
        $projects = [];
        foreach($this->pageBlockProjects as $pageBlockProject)
        {
            $projects[] = $pageBlockProject->getProject();
        }
        return $projects;
    }

    /**
     * @param Project[] $projects
     * @return PageBlockProjectList
     */
    public function setProjects($projects)
    {
        $newProjectIds = [];
        foreach($projects as $project)
        {
            $newProjectIds[] = $project->getId();
        }

        foreach($this->pageBlockProjects as $i=>$existingPageBlockProject)
        {
            if (!in_array($existingPageBlockProject->getProject()->getId(), $newProjectIds))
            {
                $this->pageBlockProjects->removeElement($existingPageBlockProject);
            }
        }

        foreach($projects as $i=>$newProject)
        {
            $pageBlockProject = $this->getPageBlockProjectContainingProject($newProject);
            if ($pageBlockProject)
            {
                $pageBlockProject->setPosition($i);
            }
            else
            {
                $pageBlockProject = new PageBlockProject();
                $pageBlockProject
                    ->setPosition($i)
                    ->setPageBlock($this)
                    ->setProject($newProject);

                $this->pageBlockProjects->add($pageBlockProject);
            }
        }

        return $this;
    }

    /**
     * @param Project $project
     * @return PageBlockProject|
     */
    private function getPageBlockProjectContainingProject($project)
    {
        foreach($this->pageBlockProjects as $pageBlockProject)
        {
            if($pageBlockProject->getProject()->getId() == $project->getId())
            {
                return $pageBlockProject;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getSelectedProjectIds()
    {
        $ids = [];
        foreach($this->getProjects() as $project)
        {
            $ids[] = $project->getId();
        }
        return $ids;
    }
}