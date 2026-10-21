<?php

namespace App\Exception;

use Exception;

class ValidationException extends Exception
{
    /**
     * @var array
     */
    private $fields;

    /**
     * ValidationException constructor.
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
        parent::__construct('Could not validate fields: ' . implode(', ',array_keys($fields)), 400);
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
}