<?php

namespace App\Repository;

use App\Model\CustomBlockField;
use App\Model\CustomBlockTemplate;

class CustomBlockTemplateRepository implements ICustomBlockTemplateRepository
{
    /**
     * @var CustomBlockTemplate[]
     */
    private $templates = [];

    /**
     * PageModuleRepository constructor.
     * @param array $customBlockTemplates
     * @throws \Exception
     */
    public function __construct(array $customBlockTemplates)
    {
        foreach($customBlockTemplates as $templateFileName => $templateData)
        {
            $fields = [];

            if(isset($templateData['fields']) && is_array($templateData['fields']))
            {
                foreach($templateData['fields']as $type => $label)
                {
                    if(in_array($type, CustomBlockField::getTypes()))
                    {
                        $fields[] = new CustomBlockField($type, $label);
                    }
                    else
                    {
                        throw new \Exception('Unknown custom block field type: ' . $type);
                    }
                }
            }
            $this->templates[] = new CustomBlockTemplate(
                $templateFileName,
                $templateData['name'],
                (int)$templateData['minWidth'],
                (int)$templateData['defaultWidth'],
                $fields,
                isset($templateData['renderContainer']) ? (bool)$templateData['renderContainer'] : true
            );
        }
    }

    /**
     * @return CustomBlockTemplate[]
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param $templateNameName
     * @return CustomBlockTemplate
     */
    public function getByTemplateName($templateNameName)
    {
        foreach($this->templates as $template)
        {
            if($template->getTemplate() == $templateNameName)
            {
                return $template;
            }
        }

        return null;
    }
}