<?php

namespace App\Model;

class CustomBlockTemplate
{
    /**
     * @var string
     */
    private $template;
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $minWidth;
    /**
     * @var int
     */
    private $defaultWidth;
    /**
     * @var CustomBlockField[]
     */
    private $fields;
    /**
     * @var bool
     */
    private $renderContainer = true;

    /**
     * CustomBlockTemplate constructor.
     * @param string $template
     * @param string $name
     * @param int $minWidth
     * @param int $defaultWidth
     * @param CustomBlockField[] $fields
     * @param $renderContainer
     */
    public function __construct($template, $name, $minWidth, $defaultWidth, $fields, $renderContainer)
    {
        $this->template = $template;
        $this->name = $name;
        $this->minWidth = $minWidth;
        $this->defaultWidth = $defaultWidth;
        $this->fields = $fields;
        $this->renderContainer = $renderContainer;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMinWidth()
    {
        return $this->minWidth;
    }

    /**
     * @return int
     */
    public function getDefaultWidth()
    {
        return $this->defaultWidth;
    }

    /**
     * @return CustomBlockField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return bool
     */
    public function getRenderContainer()
    {
        return $this->renderContainer;
    }
}