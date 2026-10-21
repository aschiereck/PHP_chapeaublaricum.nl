<?php

namespace App\Model;

class PageBlockMetaInformation implements \JsonSerializable
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $route;
    /**
     * @var string
     */
    private $model;
    /**
     * @var bool
     */
    private $canEdit;
    /**
     * @var string
     */
    private $tag;
    /**
     * @var int
     */
    private $defaultWidth;
    /**
     * @var int
     */
    private $minWidth;
    /**
     * @var string
     */
    private $template;
    /**
     * @var bool
     */
    private $renderContainer = true;

    /**
     * PageModule constructor.
     * @param string $title
     * @param string $route
     * @param string $model
     * @param bool $canEdit
     * @param string $tag
     * @param int $defaultWidth
     * @param int $minWidth
     * @param string $template
     * @param $renderContainer
     */
    public function __construct($title, $route, $model, $canEdit, $tag, $defaultWidth, $minWidth, $template, $renderContainer)
    {
        $this->title = $title;
        $this->route = $route;
        $this->model = $model;
        $this->canEdit = $canEdit;
        $this->tag = $tag;
        $this->defaultWidth = $defaultWidth;
        $this->minWidth = $minWidth;
        $this->template = $template;
        $this->renderContainer = $renderContainer;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return boolean
     */
    public function canEdit()
    {
        return $this->canEdit;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return int
     */
    public function getDefaultWidth()
    {
        return $this->defaultWidth;
    }

    /**
     * @return int
     */
    public function getMinWidth()
    {
        return $this->minWidth;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return bool
     */
    public function renderContainer()
    {
        return $this->renderContainer;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'title' => $this->getTitle(),
            'tag' => $this->getTag(),
            'defaultWidth' => $this->getDefaultWidth(),
            'minWidth' => $this->getMinWidth(),
            'canEdit' => $this->canEdit(),
            'model' => $this->getModel(),
            'route'=> $this->getRoute(),
            'template' => $this->template,
            'renderContainer' => $this->renderContainer
        ];
    }
}