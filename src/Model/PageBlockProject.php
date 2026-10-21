<?php

namespace App\Model;

use App\Model\PageBlock\PageBlockProjectList;
use Doctrine\ORM\Mapping as ORM;

/**
 * Projects that are selected in a pageBlockProjectList
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="block_project_idx", columns={"page_block_id", "project_id"})}))
 */
class PageBlockProject extends AbstractEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

     /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="\App\Model\Project", inversedBy="pageBlockProjects")
     */
    private $project;

    /**
     * @var PageBlockProjectList
     * @ORM\ManyToOne(targetEntity="\App\Model\PageBlock\PageBlockProjectList", inversedBy="pageBlockProjects")
     */
    private $pageBlock;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $position;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     * @return PageBlockProject
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return PageBlockProjectList
     */
    public function getPageBlock()
    {
        return $this->pageBlock;
    }

    /**
     * @param PageBlockProjectList $pageBlock
     * @return PageBlockProject
     */
    public function setPageBlock($pageBlock)
    {
        $this->pageBlock = $pageBlock;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return PageBlockProject
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
}