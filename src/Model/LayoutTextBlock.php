<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="tag_uix", columns={"tag", "site_id"})}))
 * @ORM\HasLifecycleCallbacks
 */
class LayoutTextBlock extends AbstractEntity
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
    private $tag;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $content = '';

    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="layoutTextBlocks")
     */
    private $site;


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

        $tagValidation = new ValidatorChain();
        $tagValidation
            ->attach(new NotEmpty());

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }
        if(!$tagValidation->isValid($this->tag))
        {
            $invalidFields['tag'] = $tagValidation->getMessages();
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
     * @return LayoutTextBlock
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return LayoutTextBlock
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
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
     * @return LayoutTextBlock
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
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
     * @return LayoutTextBlock
     */
    public function setSite(Site $site): LayoutTextBlock
    {
        $this->site = $site;
        return $this;
    }
}