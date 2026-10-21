<?php

namespace App\Model;

use App\Model\Link\AbstractLink;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class MenuItem extends AbstractEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var MenuItem
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="slides")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @var AbstractLink
     * @ORM\OneToOne(targetEntity="\App\Model\Link\AbstractLink", orphanRemoval=true, cascade={"remove", "persist"})
     * @ORM\JoinColumn(nullable=true,  onDelete="SET NULL")
     */
    private $link;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @var MenuItem[]
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $children;

    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="menuItems")
     */
    private $site;

    /**
     * MenuItem constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Validate the model
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();

        $nameValidation = new ValidatorChain();
        $nameValidation
            ->attach(new NotEmpty());

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }

        return $invalidFields;
    }

    /***************************************************************************************
     * Getters / setters
     ***************************************************************************************/

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return MenuItem
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return MenuItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param MenuItem|null $parent
     * @return MenuItem
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
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
     * @return MenuItem
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return MenuItem[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param MenuItem[] $children
     * @return MenuItem
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return AbstractLink
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param AbstractLink $link
     * @return MenuItem
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
       return count($this->getChildren()) > 0;
    }

    /**
     * @return Site
     */
    public function getSite(): Site
    {
        return $this->site;
    }

    /**
     * @param Site $site
     * @return MenuItem
     */
    public function setSite(Site $site): MenuItem
    {
        $this->site = $site;
        return $this;
    }
}