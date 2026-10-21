<?php

namespace App\Repository;

use App\Action\Admin\PageBlock\CustomTemplateAction;
use App\Model\Module;
use App\Model\PageBlock\AbstractPageBlock;
use App\Model\PageBlock\PageBlockCustomTemplate;
use App\Model\PageBlockMetaInformation;

class PageBlockMetaRepository implements IPageBlockMetaRepository
{
    /**
     * @var PageBlockMetaInformation[]
     */
    private $pageBlockTypes = [];
    /**
     * @var PageBlockMetaInformation[]
     */
    private $customBlockTypes = [];
    /**
     * @var IModuleRepository
     */
    private $moduleRepository;

    /**
     * PageModuleRepository constructor.
     * @param IModuleRepository $moduleRepository
     * @param ICustomBlockTemplateRepository $customBlockTemplateRepository
     * @param [] $pageModuleConfig
     */
    public function __construct(IModuleRepository $moduleRepository, ICustomBlockTemplateRepository $customBlockTemplateRepository, $pageBlockConfig)
    {
        //Normal blocks
        foreach ($pageBlockConfig as $pageBlockData)
        {
            $this->pageBlockTypes[] = new PageBlockMetaInformation(
                $pageBlockData['name'],
                $pageBlockData['route'],
                $pageBlockData['model'],
                $pageBlockData['canEdit'],
                $pageBlockData['tag'],
                (int)$pageBlockData['defaultWidth'],
                (int)$pageBlockData['minWidth'],
                $pageBlockData['template'],
                isset($pageBlockData['renderContainer']) ? (bool)$pageBlockData['renderContainer'] : true
            );
        }

        //Custom template modules
        foreach($customBlockTemplateRepository->getTemplates() as $template)
        {
            $this->customBlockTypes[] = new PageBlockMetaInformation(
                $template->getName(),
                CustomTemplateAction::ROUTE,
                PageBlockCustomTemplate::class,
                !empty($template->getFields()),
                Module::PAGE,
                $template->getDefaultWidth(),
                $template->getMinWidth(),
                $template->getTemplate(),
                $template->getRenderContainer()
            );
        }

        $this->moduleRepository = $moduleRepository;
    }

    /**
     * @return PageBlockMetaInformation[]
     */
    public function getAllPageBlockMetaInformation()
    {
        //Set custom modules
        $activeModules = $this->customBlockTypes;

        //Add default modules
        foreach($this->pageBlockTypes as $module)
        {
            if($this->moduleRepository->isEnabled($module->getTag()))
            {
                $activeModules[] = $module;
            }
        }

        return $activeModules;
    }

    /**
     * @param AbstractPageBlock $model
     * @return bool
     * @throws \Exception
     */
    public function renderContainer($model)
    {
        $className = get_class($model);

        if($className == PageBlockCustomTemplate::class)
        {
            /** @var PageBlockCustomTemplate $customPageBlock */
            $customPageBlock = $model;

            foreach($this->customBlockTypes as $customBlockType)
            {
                if($customPageBlock->getTemplate() == $customBlockType->getTemplate())
                {
                    return $customBlockType->renderContainer();
                }
            }
        }
        else
        {
            foreach($this->pageBlockTypes as $pageBlockTypes)
            {
                if($pageBlockTypes->getModel() == $className)
                {
                    return $pageBlockTypes->renderContainer();
                }
            }
        }

        throw new \Exception('Unable to find block: ' . $className);
    }

    /**
     * @param AbstractPageBlock $model
     * @return string
     */
    public function getModuleTagByModel($model)
    {
        $className = get_class($model);

        if($className == PageBlockCustomTemplate::class)
        {
            return Module::PAGE;
        }
        else
        {
            foreach($this->pageBlockTypes as $pageBlockTypes)
            {
                if($pageBlockTypes->getModel() == $className)
                {
                    return $pageBlockTypes->getTag();
                }
            }
        }

        return null;
    }
}