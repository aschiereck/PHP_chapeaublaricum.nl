<?php

namespace App\View\Admin\Helper;

use Zend\View\Helper\AbstractHelper;

class FormHelper extends AbstractHelper
{
    private $_validationErrors = [];

    /**
     * @param [] $errors
     */
    public function setValidationErrors($errors)
    {
        $this->_validationErrors = $errors;
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param bool $isRequired
     * @return string
     */
    public function richText($name, $label, $value='', $isRequired=false)
    {
        $required = $isRequired ? 'required' : '';
        $element = '<textarea id="fc-'.$name.'" rows="4" name="'.$name.'" '.$required.' class="form-control rich-text">'.htmlspecialchars($value).'</textarea>';

        return $this->wrapElement($name, $label, $element);
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param bool $isRequired
     * @return string
     */
    public function textarea($name, $label, $value='', $isRequired=false)
    {
        $required = $isRequired ? 'required' : '';
        $element = '<textarea id="fc-'.$name.'" rows="4" name="'.$name.'" '.$required.' class="form-control">'.htmlspecialchars($value).'</textarea>';

        $wrapperClasses = [];
        if($isRequired)
        {
            $wrapperClasses[] = 'required';
        }

        return $this->wrapElement($name, $label, $element, $wrapperClasses);
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param bool $isRequired
     * @return string
     */
    public function checkbox($name, $label, $value, $isRequired=false)
    {
        $required = $isRequired ? 'required' : '';
        $checked = $value ? 'checked' : '';

        $element = '<div class="checkbox checkbox-inline">';
        $element .= '<input type="checkbox" class="styled" id="fc-'.$name.'" '.$required.' name="'.$name.'" '.$checked.' value="1">';
        $element .= '<label for="fc-'.$name.'">'.$label.'</label>';
        $element .= '</div>';

        return $this->wrapElement($name, false, $element);
    }

    /**
     * @param $name
     * @param $label
     * @param $options
     * @param $value
     * @param bool $isRequired
     * @return string
     */
    public function select($name, $label, $options, $value, $isRequired=false)
    {
        $required = $isRequired ? 'required' : '';
        $element = '<select id="fc-'.$name.'" name="'.$name.'" '.$required.' class="form-control selectize">';

        foreach($options as $k=>$v)
        {
            $selected = ($k == $value) ? 'selected' : '';
            $element .= '<option '.$selected.' value="'.$k.'">'.$v.'</option>';
        }

        $element .= '</select>';
        return $this->wrapElement($name, $label, $element);
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $options
     * @param array $values
     * @param bool $isRequired
     * @return string
     */
    public function multiSelect($name, $label, $options, $values, $isRequired=false)
    {
        $required = $isRequired ? 'required' : '';
        $element = '<select multiple="multiple" id="fc-'.$name.'" name="'.$name.'[]" '.$required.' class="form-control selectize">';

        foreach($options as $k=>$v)
        {
            $selected = in_array($k, $values) ? 'selected' : '';
            $element .= '<option '.$selected.' value="'.$k.'">'.$v.'</option>';
        }

        $element .= '</select>';
        return $this->wrapElement($name, $label, $element);
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param bool $isRequired
     * @return string
     */
    public function date($name, $label, $value='', $isRequired=false)
    {
        return $this->createInput($name, $label, 'text', $value, $isRequired, ['datepicker'], ['data-value'=>$value]);
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param bool $isRequired
     * @return string
     */
    public function time($name, $label, $value='', $isRequired=false)
    {
        return $this->createInput($name, $label, 'text', $value, $isRequired, ['timepicker']);
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param string[] $listeners
     * @return string
     */
    public function seoFocusWords($name, $label, $value='', $listeners)
    {
        $classes = [
            'seo-form-control'
        ];
        $attrs = [
            'data-seo-listeners' => implode($listeners, ',')
        ];

        return $this->createInput($name, $label, 'text', $value, false, $classes, $attrs);
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param bool $isRequired
     * @return string
     */
    public function text($name, $label, $value='', $isRequired=false)
    {
        return $this->createInput($name, $label, 'text', $value, $isRequired, [], ['maxlength'=>250]);
    }
    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param bool $isRequired
     * @return string
     */
    public function number($name, $label, $value='', $isRequired=false)
    {
        return $this->createInput($name, $label, 'number', $value, $isRequired);
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param bool $isRequired
     * @return string
     */
    public function metaDescription($name, $label, $value='', $isRequired=false)
    {
        $required = $isRequired ? 'required' : '';
        $element = '<textarea id="fc-'.$name.'" rows="4" name="'.$name.'" '.$required.' class="form-control meta-description">'.htmlspecialchars($value).'</textarea>';

        $wrapperClasses = [];
        if($isRequired)
        {
            $wrapperClasses[] = 'required';
        }

        return $this->wrapElement($name, $label, $element, $wrapperClasses);
    }

    /**
     * @param $name
     * @param $label
     * @param bool $isRequired
     * @return string
     */
    public function file($name, $label, $isRequired=false)
    {
        return $this->createInput($name, $label, 'file','',$isRequired);
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param bool $isRequired
     * @return string
     */
    public function email($name, $label, $value='', $isRequired=false)
    {
        return $this->createInput($name, $label, 'email', $value, $isRequired);
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param bool $isRequired
     * @return string
     */
    public function password($name, $label, $value='', $isRequired=false)
    {
        return $this->createInput($name, $label, 'password', $value, $isRequired);
    }

    /**
     * @param $name
     * @param $label
     * @param string $value
     * @param $listenTo
     * @param bool $isRequired
     * @return string
     */
    public function url($name, $label, $value, $listenTo, $isRequired=false)
    {
        return $this->createInput($name, $label, 'text', $value, $isRequired, [], ['data-url-listener'=>'fc-' . $listenTo]);
    }

    /**
     * @param $name
     * @param $label
     * @param $type
     * @param string $value
     * @param bool $isRequired
     * @param array $classes
     * @param array $attributes
     * @return string
     */
    private function createInput($name, $label, $type, $value='', $isRequired=false, $classes=[], $attributes=[])
    {
        $classes[] = 'form-control';

        $attributes['id'] = 'fc-'.$name;
        $attributes['value'] = htmlspecialchars($value);
        $attributes['type'] = $type;
        $attributes['name'] = $name;
        $attributes['class'] = implode(' ', $classes);

        if($isRequired)
        {
            $attributes['required'] = 'required';
        }

        $attributesHtml = '';
        foreach($attributes as $attributeName=>$attributeValue)
        {
            $attributesHtml .= ' ' . $attributeName . '="' . $attributeValue . '"';
        }
        $element = '<input'.$attributesHtml.'>';

        $wrapperClasses = [];
        if($isRequired)
        {
            $wrapperClasses[] = 'required';
        }

        return $this->wrapElement($name, $label, $element, $wrapperClasses);
    }

    /**
     * @param $name
     * @param $label
     * @param $element
     * @param array $wrapperClasses
     * @return string
     */
    private function wrapElement($name, $label, $element, $wrapperClasses = [])
    {
        $labelHtml = '';
        if($label)
        {
            $labelHtml = '<label for="fc-' . $name . '">' . $label . '</label>';
        }

        $wrapperClasses[] = 'form-group';
        $errorHtml = '';

        if(isset($this->_validationErrors[$name]))
        {
            foreach($this->_validationErrors[$name] as $error)
            {
                $errorHtml .= '<div class="form-control-feedback">'.$error.'</div>';
            }
            $wrapperClasses[] = 'has-danger';
        }

        return '<div class="'.implode(' ', $wrapperClasses).'">' . $labelHtml . $element . $errorHtml . '</div>';
    }
}

