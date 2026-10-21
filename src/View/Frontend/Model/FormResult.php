<?php

namespace App\View\Frontend\Model;

class FormResult
{
    /**
     * @var int
     */
    private $formId;
    /**
     * @var bool
     */
    private $isSuccess;

    /**
     * FormResult constructor.
     * @param int $formId
     * @param bool $isSuccess
     */
    public function __construct($formId, $isSuccess)
    {
        $this->formId = $formId;
        $this->isSuccess = $isSuccess;
    }

    /**
     * @return int
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->isSuccess;
    }
}