<?php

namespace App\View\Frontend\Helper;

use App\Model\Form;
use App\Model\FormControl;
use App\Service\Translation\ITranslationService;
use App\Session\ISessionManager;
use App\View\Frontend\Model\FormResult;
use Zend\View\Helper\AbstractHelper;

class FormHelper extends AbstractHelper
{
    /**
     * @var ISessionManager
     */
    private $sessionManager;
    /**
     * @var ITranslationService
     */
    private $translationService;

    /**
     * @var FormResult
     */
    private $formResult;
    /**
     * @var string
     */
    private $recaptchaSiteKey;

    /**
     * FormRenderer constructor.
     * @param ISessionManager $sessionManager
     * @param ITranslationService $translationService
     * @param string $recaptchaSiteKey
     */
    public function __construct(ISessionManager $sessionManager, ITranslationService $translationService, $recaptchaSiteKey)
    {
        $this->sessionManager = $sessionManager;
        $this->translationService = $translationService;
        $this->recaptchaSiteKey = $recaptchaSiteKey;
    }

    /**
     * @param Form $form
     * @param FormResult[] $formResults
     */
    public function setFormResults($form, $formResults)
    {
        if($formResults)
        {
            foreach ($formResults as $formResult)
            {
                if ($formResult->getFormId() == $form->getId())
                {
                    $this->formResult = $formResult;
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function showErrorMessage()
    {
        if($this->formResult && !$this->formResult->isSuccess())
        {
            return true;
        }
        return false;
    }

    /**
     * @param FormControl $control
     * @return string
     * @throws \Exception
     */
    public function renderControl($control)
    {
        switch($control->getType())
        {
            case FormControl::TYPE_TEXT:
                $html = $this->renderInput('text', $control);
                break;
            case FormControl::TYPE_TEXT_AREA:
                $html = $this->renderTextArea($control);
                break;
            case FormControl::TYPE_EMAIL:
                $html = $this->renderInput('email', $control);
                break;
            case FormControl::TYPE_LIST_SINGLE:
                $html = $this->renderSingleSelectionList($control);
                break;
            case FormControl::TYPE_LIST_MULTIPLE:
                $html = $this->renderMultipleSelectionList($control);
                break;
            case FormControl::TYPE_FILE:
                $html = $this->renderInput('file', $control);
                break;
            default:
                throw new \Exception('Unknown form control type: ' . $control->getType());
        }

        return $html;
    }

    /**
     * @param Form $form
     * @return string
     */
    public function getFormIdField(Form $form)
    {
        return '<input type="hidden" name="form" value="'.$form->getId().'"/>';
    }

    /**
     * @param string $type
     * @param FormControl $control
     * @return string
     */
    private function renderInput($type, $control)
    {
        $name = $this->getName($control);
        $value = $this->getValue($name);
        $required = ($control->isRequired() ? 'required' : '');
        $label = $this->createLabel($name, $control->getLabel(), $control->isRequired());
        $element = '<input name="'.$name.'" type="'.$type.'" class="form-control" '.$required.' value="'.$value.'" placeholder="'.$control->getLabel().'"/>';
        return $label . $element;
    }

    /**
     * @param FormControl $control
     * @return string
     */
    private function renderTextArea($control)
    {
        $name = $this->getName($control);
        $value = $this->getValue($name);
        $required = ($control->isRequired() ? 'required' : '');
        $label = $this->createLabel($name, $control->getLabel(), $control->isRequired());
        $element = '<textarea name="'.$name.'" class="form-control"  '.$required.' rows="4">'.$value.'</textarea>';
        return $label . $element;
    }

    /**
     * @param FormControl $control
     * @return string
     */
    private function renderSingleSelectionList($control)
    {
        $name = $this->getName($control);
        $value = $this->getValue($name);
        $required = ($control->isRequired() ? 'required' : '');

        $label = '<label for="text">'.$control->getLabel().'</label>';
        $element = '<select name="'.$name.'" class="form-control" '.$required.'>';

        foreach(explode(PHP_EOL, $control->getListOptions()) as $k=>$v)
        {
            $selected = ($value != '' && $value == $k) ? 'selected' : '';
            $element .=  '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
        }

        $element .= '</select>';

        return $label . $element;
    }

    /**
     * @param FormControl $control
     * @return string
     */
    private function renderMultipleSelectionList($control)
    {
        $name = $this->getName($control);
        $value = $this->getValue($name);
        $label = $this->createLabel($name, $control->getLabel(), $control->isRequired());
        $element = '';

        foreach(explode(PHP_EOL, $control->getListOptions()) as $k=>$v)
        {
            $checked = ($value != '' && $value == $k) ? 'checked' : '';
            $element .= '<div class="checkbox">';
            $element .= '<label><input type="checkbox" value="'.$k.'" name="'.$name.'[]" '.$checked.'>'.$v.'</label>';
            $element .= '</div>';
        }

        return $label . $element;
    }

    /**
     * @param string $for
     * @param string $content
     * @param bool $isFieldRequired
     * @return string
     */
    private function createLabel($for, $content, $isFieldRequired)
    {
        return '<label for="'.$for.'">'.$content.($isFieldRequired ? '<span class="required"></span>': '').'</label>';
    }

    /**
     * @param $name
     * @return string
     */
    private function getValue($name)
    {
        $rawValue = isset($_POST[$name]) ? $_POST[$name] : '';
        if(is_array($rawValue))
        {
            foreach($rawValue as $i=>$v)
            {
                $rawValue[$i] = htmlspecialchars($v);
            }
        }
        else
        {
            $rawValue = htmlspecialchars($rawValue);
        }
        return $rawValue;
    }

    /**
     * @param FormControl $control
     * @return int
     */
    private function getName($control)
    {
        return $control->getId();
    }

    /**
     * @return string
     */
    public function renderRecaptcha()
    {
        return $this->recaptchaSiteKey
            ? '<div class="g-recaptcha" data-sitekey="'.$this->recaptchaSiteKey.'"></div>'
            : '';
    }
}