<?php

namespace App\Model;

use App\Model\PageBlock\PageBlockForm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class Form extends AbstractEntity
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
    private $receiver;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $sendResponseMessage = false;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $responseEmailMessage = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $responseEmailTitle = '';
    /**
     * @var FormControl[]
     * @ORM\OneToMany(targetEntity="FormControl", mappedBy="form", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $formControls;

    /**
     * @var PageBlockForm[]
     * @ORM\OneToMany(targetEntity="\App\Model\PageBlock\PageBlockForm", mappedBy="form", cascade={"remove"})
     */
    private $pageBlocks;

    /**
     * @var Page
     * @ORM\ManyToOne(targetEntity="\App\Model\Page")
     * @ORM\JoinColumn(nullable=true,  onDelete="SET NULL")
     */
    private $page;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isRecaptchaEnabled = false;

    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="forms")
     */
    private $site;

    /**
     * Slider constructor.
     */
    public function __construct()
    {
        $this->formControls = new ArrayCollection();
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

        $emailValidation = new ValidatorChain();
        $emailValidation
            ->attach(new NotEmpty())
            ->attach(new EmailAddress());

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }

        foreach(explode(';', $this->receiver) as $emailAddress)
        {
            if(!$emailValidation->isValid(trim($emailAddress)))
            {
                $invalidFields['receiver'] = $emailValidation->getMessages();
            }
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
     * @return Form
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param string $receiver
     * @return Form
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * @return FormControl[]
     */
    public function getFormControls()
    {
        return $this->formControls;
    }

    /**
     * @param FormControl[] $formControls
     * @return Form
     */
    public function setFormControls($formControls)
    {
        $this->formControls = $formControls;
        return $this;
    }

    /**
     * @return PageBlockForm[]
     */
    public function getPageBlocks()
    {
        return $this->pageBlocks;
    }

    /**
     * @return string
     */
    public function getUsedOnText()
    {
        $names = [];
        foreach($this->pageBlocks as $pageBlock)
        {
            $names[] = $pageBlock->getPage()->getName();
        }
        return implode(', ', $names);
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
     * @return Form
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSendResponseMessage()
    {
        return $this->sendResponseMessage;
    }

    /**
     * @param boolean $sendResponseMessage
     * @return Form
     */
    public function setSendResponseMessage($sendResponseMessage)
    {
        $this->sendResponseMessage = $sendResponseMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseEmailMessage()
    {
        return $this->responseEmailMessage;
    }

    /**
     * @param string $responseEmailMessage
     * @return Form
     */
    public function setResponseEmailMessage($responseEmailMessage)
    {
        $this->responseEmailMessage = $responseEmailMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseEmailTitle()
    {
        return $this->responseEmailTitle;
    }

    /**
     * @param string $responseEmailTitle
     * @return Form
     */
    public function setResponseEmailTitle($responseEmailTitle)
    {
        $this->responseEmailTitle = $responseEmailTitle;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRecaptchaEnabled()
    {
        return $this->isRecaptchaEnabled;
    }

    /**
     * @param bool $isRecaptchaEnabled
     * @return Form
     */
    public function setIsRecaptchaEnabled($isRecaptchaEnabled)
    {
        $this->isRecaptchaEnabled = $isRecaptchaEnabled;
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
     * @return Form
     */
    public function setSite(Site $site): Form
    {
        $this->site = $site;
        return $this;
    }
}
