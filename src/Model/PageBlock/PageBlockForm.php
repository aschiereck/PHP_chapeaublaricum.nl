<?php

namespace App\Model\PageBlock;

use App\Model\Form;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PageBlockForm extends AbstractPageBlock
{
    const TEMPLATE = 'form';

    /**
     * @var Form
     * @ORM\ManyToOne(targetEntity="\App\Model\Form", inversedBy="pageBlocks")
     */
    private $form;

    /**
     * PageBlockCarousel constructor.
     */
    public function __construct()
    {
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
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Form $form
     * @return PageBlockForm
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @return string
     */
    public function getEditorTitle()
    {
        return $this->form ? $this->form->getName() : '';
    }
}