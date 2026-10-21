<?php

namespace App\Model;

use App\Model\Image\TeamMemberImage;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class TeamMember extends AbstractEntity
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
    private $function;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $summary;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $moreInformationUrl;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $twitterUrl;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $linkedinUrl;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $flickerUrl;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @var TeamMemberImage
     * @ORM\OneToOne(targetEntity="\App\Model\Image\TeamMemberImage", orphanRemoval=true, cascade={"remove"})
     */
    private $image;
    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="teamMembers")
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

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }

        return $invalidFields;
    }

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
     * @return TeamMember
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param string $function
     * @return TeamMember
     */
    public function setFunction($function)
    {
        $this->function = $function;
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
     * @return TeamMember
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return string
     */
    public function getMoreInformationUrl()
    {
        return $this->moreInformationUrl;
    }

    /**
     * @param string $moreInformationUrl
     * @return TeamMember
     */
    public function setMoreInformationUrl($moreInformationUrl)
    {
        $this->moreInformationUrl = $moreInformationUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getTwitterUrl()
    {
        return $this->twitterUrl;
    }

    /**
     * @param string $twitterUrl
     * @return TeamMember
     */
    public function setTwitterUrl($twitterUrl)
    {
        $this->twitterUrl = $twitterUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getLinkedinUrl()
    {
        return $this->linkedinUrl;
    }

    /**
     * @param string $linkedinUrl
     * @return TeamMember
     */
    public function setLinkedinUrl($linkedinUrl)
    {
        $this->linkedinUrl = $linkedinUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getFlickerUrl()
    {
        return $this->flickerUrl;
    }

    /**
     * @param string $flickerUrl
     * @return TeamMember
     */
    public function setFlickerUrl($flickerUrl)
    {
        $this->flickerUrl = $flickerUrl;
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
     * @return TeamMember
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return TeamMemberImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param TeamMemberImage $image
     * @return TeamMember
     */
    public function setImage($image)
    {
        $this->image = $image;
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
     * @return TeamMember
     */
    public function setSite(Site $site): TeamMember
    {
        $this->site = $site;
        return $this;
    }
}
