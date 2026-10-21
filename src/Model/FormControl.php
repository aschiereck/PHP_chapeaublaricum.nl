<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @Orm\HasLifecycleCallbacks
 */
class FormControl extends AbstractEntity
{
    const TYPE_TEXT = 'text';
    const TYPE_EMAIL = 'email';
    const TYPE_LIST_SINGLE = 'list_single';
    const TYPE_LIST_MULTIPLE = 'list_multiple';
    const TYPE_TEXT_AREA = 'text_area';
    const TYPE_FILE = 'file';

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
    private $label = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $type = '';

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $listOptions = '';

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isRequired;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @var Form
     * @ORM\ManyToOne(targetEntity="Form", inversedBy="formControls")
     */
    private $form;


    /**
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();

        $labelValidation = new ValidatorChain();
        $labelValidation
            ->attach(new NotEmpty());

        $typeValidation = new ValidatorChain();
        $typeValidation
            ->attach(new NotEmpty())
            ->attach(new InArray(['hayStack'=>array_keys($this->getTypeList())]));

        $listOptionsValidation = new ValidatorChain();
        $listOptionsValidation
            ->attach(new NotEmpty());

        if(!$labelValidation->isValid($this->label))
        {
            $invalidFields['label'] = $labelValidation->getMessages();
        }
        if(!$typeValidation->isValid($this->type))
        {
            $invalidFields['type'] = $typeValidation->getMessages();
        }
        if($this->type == self::TYPE_LIST_MULTIPLE || $this->type == self::TYPE_LIST_SINGLE)
        {
            if(!$listOptionsValidation->isValid($this->type))
            {
                $invalidFields['listOptions'] = $listOptionsValidation->getMessages();
            }
        }

        return $invalidFields;
    }

    /**
     * @return array
     */
    public function getTypeList()
    {
        return [
            self::TYPE_TEXT => 'Tekst (&eacute;&eacute;n regel)',
            self::TYPE_TEXT_AREA => 'Tekst (meerdere regels)',
            self::TYPE_EMAIL => 'Email',
            self::TYPE_LIST_SINGLE => 'Lijst (enkele selectie)',
            self::TYPE_LIST_MULTIPLE => 'Lijst (multi selectie)',
            self::TYPE_FILE => 'Bestand'
        ];
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return FormControl
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return FormControl
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getListOptions()
    {
        return $this->listOptions;
    }

    /**
     * @param $index
     * @return string
     */
    public function getListOption($index)
    {
        $parts = explode(PHP_EOL, $this->getListOptions());
        return isset($parts[$index]) ? $parts[$index] : '';
    }

    /**
     * @param string $listOptions
     * @return FormControl
     */
    public function setListOptions($listOptions)
    {
        $this->listOptions = $listOptions;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->isRequired;
    }

    /**
     * @param boolean $isRequired
     * @return FormControl
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;
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
     * @return FormControl
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Form $form
     * @return FormControl
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @return bool
     */
    public function isListType()
    {
        return in_array($this->getType(), [self::TYPE_LIST_SINGLE, self::TYPE_LIST_MULTIPLE]);
    }
}
