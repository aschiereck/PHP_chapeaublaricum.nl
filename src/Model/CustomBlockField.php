<?php

namespace App\Model;

class CustomBlockField
{
    const TYPE_TEXT = 'text';
    const TYPE_RICHTEXT = 'richtext';
    const TYPE_LINK = 'link';
    const TYPE_IMAGE = 'image';

    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $label;

    /**
     * CustomBlockField constructor.
     * @param string $type
     * @param string $label
     */
    public function __construct($type, $label)
    {
        $this->type = $type;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_IMAGE,
            self::TYPE_LINK,
            self::TYPE_TEXT,
            self::TYPE_RICHTEXT
        ];
    }
}