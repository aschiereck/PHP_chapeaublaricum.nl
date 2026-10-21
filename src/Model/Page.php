<?php

namespace App\Model;

use App\Model\PageBlock\AbstractPageBlock;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\Uri;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="url_idx", columns={"site_id", "url"})}))
 */
class Page extends AbstractEntity
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
     * @var string
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $headTitle = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $metaDescription = '';

    /**
     * @var AbstractPageBlock[]
     * @ORM\OneToMany(targetEntity="\App\Model\PageBlock\AbstractPageBlock", mappedBy="page", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"y" = "ASC", "x"="ASC"})
     *
     */
    private $pageBlocks;

    /**
     * @var AbstractPageBlock[]
     * @ORM\OneToMany(targetEntity="\App\Model\Link\PageLink", mappedBy="page", cascade={"remove"})
     * Describe relations for cascade removal
     */
    private $pageLink;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $allowSeoIndex = true;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $modifiedDate;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $focusWords = '';

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $seoCompleteness;

    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="pages")
     */
    private $site;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->pageBlocks = new ArrayCollection();
    }

    /**
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();

        $nameValidation = new ValidatorChain();
        $nameValidation
            ->attach(new NotEmpty());

        $urlValidation = new ValidatorChain();
        $urlValidation
            ->attach(new Uri(['allowRelative'=>true]));

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }
        if($this->url != '' && !$urlValidation->isValid($this->url))
        {
            $invalidFields['url'] = $urlValidation->getMessages();
        }

        return $invalidFields;
    }

    #region Getters / setters

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
     * @return Page
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeadTitle()
    {
        return $this->headTitle;
    }

    /**
     * @param string $headTitle
     * @return Page
     */
    public function setHeadTitle($headTitle)
    {
        $this->headTitle = (string)$headTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     * @return Page
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = (string)$metaDescription;
        return $this;
    }

    /**
     * @return AbstractPageBlock[]
     */
    public function getPageBlocks()
    {
        return $this->pageBlocks;
    }

    /**
     * @param AbstractPageBlock[] $pageBlocks
     * @return Page
     */
    public function setPageBlocks($pageBlocks)
    {
        $newBlocks = new ArrayCollection($pageBlocks);

        foreach($this->pageBlocks as $existingBlock)
        {
            if(!$newBlocks->contains($existingBlock))
            {
                $this->pageBlocks->removeElement($existingBlock);
            }
        }

        foreach($newBlocks as $newBlock)
        {
            if(!$this->pageBlocks->contains($newBlock))
            {
                $this->pageBlocks->add($newBlock);
            }
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function allowSeoIndex()
    {
        return $this->allowSeoIndex;
    }

    /**
     * @param boolean $allowSeoIndex
     * @return Page
     */
    public function setAllowSeoIndex($allowSeoIndex)
    {
        $this->allowSeoIndex = (bool)$allowSeoIndex;
        return $this;
    }

    /**
     * @return PageBlock\AbstractPageBlock[]
     */
    public function getPageLink()
    {
        return $this->pageLink;
    }

    /**
     * @param PageBlock\AbstractPageBlock[] $pageLink
     * @return Page
     */
    public function setPageLink($pageLink)
    {
        $this->pageLink = $pageLink;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * @param DateTime $modifiedDate
     * @return Page
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeoCompleteness()
    {
        return $this->seoCompleteness;
    }

    /**
     * @param int $seoCompleteness
     * @return Page
     */
    public function setSeoCompleteness($seoCompleteness)
    {
        $this->seoCompleteness = $seoCompleteness;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getFocusWords()
    {
        return implode(', ', explode(',', $this->focusWords));
    }

    /**
     * @param string $focusWordsAsString
     * @return Page
     */
    public function setFocusWords($focusWordsAsString)
    {
        $focusWords = explode(',', $focusWordsAsString);
        $focusWords = array_map('trim', $focusWords);
        $focusWords = (string)implode(',', $focusWords);
        $this->focusWords = $focusWords;

        return $this;
    }

    /**
     * @return array
     */
    public function getFocusWordsList()
    {
        if(!$this->focusWords)
        {
            return [];
        }
        else
        {
            return explode(',', $this->focusWords);
        }
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
     * @return Page
     */
    public function setSite(Site $site): Page
    {
        $this->site = $site;
        return $this;
    }
}
