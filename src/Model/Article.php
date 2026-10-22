<?php

namespace App\Model;

use App\Model\Image\ArticleImage;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\Uri;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="url_idx", columns={"site_id", "url"})}))
 */
class Article extends AbstractEntity
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
    private $summary;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $headTitle;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $metaDescription;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $publishDate;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isPublished = false;

    /**
     * @var ArticleImage
     * @ORM\OneToOne(targetEntity="\App\Model\Image\ArticleImage", orphanRemoval=true, cascade={"remove"})
     */
    private $articleImage;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $modifiedDate;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $publishOnFacebook = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $publishOnTwitter = false;

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
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="articles")
     */
    private $site;

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
            ->attach(new NotEmpty())
            ->attach(new Uri(['allowRelative'=>true]));

        $articleImageValidation = new ValidatorChain();
        $articleImageValidation
            ->attach(new NotEmpty());

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }
        if(!$urlValidation->isValid($this->url))
        {
            $invalidFields['url'] = $urlValidation->getMessages();
        }
        if(!$articleImageValidation->isValid($this->articleImage))
        {
            $invalidFields['articleImage'] = $articleImageValidation->getMessages();
        }


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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Article
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
     * @return Article
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
     * @return Article
     */
    public function setHeadTitle($headTitle)
    {
        $this->headTitle = $headTitle;
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
     * @return Article
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * @param DateTime $publishDate
     * @return Article
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param boolean $isPublished
     * @return Article
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return Article
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return ArticleImage
     */
    public function getArticleImage()
    {
        return $this->articleImage;
    }

    /**
     * @param ArticleImage $articleImage
     * @return Article
     */
    public function setArticleImage($articleImage)
    {
        $this->articleImage = $articleImage;
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
     * @return Article
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPublishOnFacebook()
    {
        return $this->publishOnFacebook;
    }

    /**
     * @param boolean $publishOnFacebook
     * @return Article
     */
    public function setPublishOnFacebook($publishOnFacebook)
    {
        $this->publishOnFacebook = (bool)$publishOnFacebook;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPublishOnTwitter()
    {
        return $this->publishOnTwitter;
    }

    /**
     * @param boolean $publishOnTwitter
     * @return Article
     */
    public function setPublishOnTwitter($publishOnTwitter)
    {
        $this->publishOnTwitter = (bool)$publishOnTwitter;
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
     * @return Article
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
        return implode(', ', explode(',', (string)$this->focusWords));
    }

    /**
     * @param string $focusWordsAsString
     * @return Article
     */
    public function setFocusWords($focusWordsAsString)
    {
        $focusWords = explode(',', (string)$focusWordsAsString);
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
     * @return Article
     */
    public function setSite(Site $site): Article
    {
        $this->site = $site;
        return $this;
    }
}
