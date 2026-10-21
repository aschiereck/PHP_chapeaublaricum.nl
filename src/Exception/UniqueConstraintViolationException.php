<?php

namespace App\Exception;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException as DoctrineException;
use Exception;

class UniqueConstraintViolationException extends Exception
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * UniqueConstraintViolationException constructor.
     * @param DoctrineException $originalException
     * @param string $fieldName
     */
    public function __construct($originalException, $fieldName)
    {
        parent::__construct($originalException->getMessage(), $originalException->getCode(), $originalException);
        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }
}