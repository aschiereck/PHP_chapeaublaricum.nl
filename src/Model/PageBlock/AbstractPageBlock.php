<?php

namespace App\Model\PageBlock;

use App\Model\AbstractEntity;
use App\Model\Page;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "PageBlockSlider" = "PageBlockSlider",
 *     "PageBlockForm" = "PageBlockForm",
 *     "PageBlockNewsArchive" = "PageBlockNewsArchive",
 *     "PageBlockRecentArticles" = "PageBlockRecentArticles",
 *     "PageBlockPhotoAlbum" = "PageBlockPhotoAlbum",
 *     "PageBlockCarousel" = "PageBlockCarousel",
 *     "PageBlockTeam" = "PageBlockTeam",
 *     "PageBlockCustomTemplate" = "PageBlockCustomTemplate",
 *     "PageBlockProjectList" = "PageBlockProjectList",
 *     "PageBlockCalendar" = "PageBlockCalendar",
 *     "PageBlockUpcomingEvents" = "PageBlockUpcomingEvents"
 * })
 * @ORM\Table(name="page_block")
 */
abstract class AbstractPageBlock extends AbstractEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Page
     * @ORM\ManyToOne(targetEntity="\App\Model\Page", inversedBy="pageBlocks")
     */
    private $page;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $width;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $x;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $y;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $template;


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
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Page $page
     * @return AbstractPageBlock
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return AbstractPageBlock
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     * @return AbstractPageBlock
     */
    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     * @return AbstractPageBlock
     */
    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }

    /**
     * @return string
     */
    abstract public function getEditorTitle();

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     * @return AbstractPageBlock
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
}
