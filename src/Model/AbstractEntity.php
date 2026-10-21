<?php

namespace App\Model;

use App\Exception\ValidationException;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    /**
     * @ORM\preRemove
     */
    public function __beforeRemove()
    {}

    /**
     * @ORM\postRemove
     */
    public function __afterRemove()
    {}

    /**
     * @ORM\prePersist
     */
    public function __beforeCreate()
    {
        if(!$this->isValid())
        {
            throw new ValidationException($this->getValidationErrors());
        }
    }

    /**
     * @ORM\postPersist
     */
    public function __afterCreate()
    {}

    /**
     * @ORM\preUpdate
     */
    public function __beforeUpdate()
    {
        if(!$this->isValid())
        {
            throw new ValidationException($this->getValidationErrors());
        }
    }

    /**
     * @ORM\postUpdate
     */
    public function __afterUpdate()
    {}

    /**
     * @ORM\postLoad
     */
    public function __afterLoad()
    {}

    /**
     * @return bool
     */
    public function isValid()
    {
        return empty($this->__validate());
    }

    /**
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->__validate();
    }

    /**
     * Validate the model
     * @return array
     */
    abstract protected function __validate();
}

